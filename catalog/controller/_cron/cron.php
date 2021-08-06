<?php
class ControllerCronCron extends Controller {

	public function clearCustomerReward() {

		$this->load->model('account/customer_group');

		$this->load->model('account/customer');

		$this->load->model('account/customer_group');

		$this->load->language('account/account');

		$customer_groups = $this->model_account_customer_group->getCustomerGroups();

		foreach ($customer_groups as $customer_group) {

			$today = date('Y-m-d');
			$customer_group_id = $customer_group['customer_group_id'];

			// Retrieves customer group reward point dates (start date, end date, clear date) by customer group
			$reward_dates = $this->model_account_customer_group->getCustomerGroupRewardDates($customer_group_id);

			foreach($reward_dates as $reward_date) {
				$start_date = $reward_date['start_date'];
				$end_date = $reward_date['end_date'];
				$clear_date = $reward_date['clear_date'];

				// $start_date = $customer_group['start_date'];
				// $end_date = $customer_group['end_date'];
				// $clear_date = $customer_group['clear_date'];

				if ($today == $clear_date) {
					/* clear reward from date1 to date2 for customer who is within the customer group */

					$customers = $this->model_account_customer->getCustomersByCustomerGroup($customer_group_id);

					foreach ($customers as $customer) {
						
						$customer_id = $customer['customer_id'];
						
						$points = $this->model_account_customer->getRewardTotalByCustomerId($customer_id, $start_date, $end_date);

						if ($points > 0) {
							$this->model_account_customer->clearReward($customer_id, $points, $this->language->get('text_clear_reward'));
						}

					} /* foreach customer */

				} /* if today = clear date*/
			
			} /* foreach reward dates */

		} /* foreach customer */
	}

	public function notifyLowStock() {

		$notify = $this->config->get('config_low_stock_notify');

		if ($notify) {

			$low_stock_quantity = $this->config->get('config_low_stock_quantity');

			$this->load->model('catalog/product');

			$this->load->language('mail/product');

			$low_stock_products = $this->model_catalog_product->getLowStockProducts($low_stock_quantity);

			if($low_stock_products) {

				$product_table = '<table class="invoice">';
				$product_table .= '<thead>';
				$product_table .= '<tr>';
				$product_table .= '<td>'. $this->language->get('column_product_id') .'</td>';
				$product_table .= '<td class="text-left">'. $this->language->get('column_name') .'</td>';
				$product_table .= '<td>'. $this->language->get('column_sku') .'</td>';
				$product_table .= '<td>'. $this->language->get('column_model') .'</td>';
				$product_table .= '<td>'. $this->language->get('column_price') .'</td>';
				$product_table .= '<td>'. $this->language->get('column_quantity') .'</td>';
				$product_table .= '</tr>';
				$product_table .= '</thead>';

				$product_table .= '<tbody>';
				foreach ($low_stock_products as $product) {
					$product_table .= '<tr>';
			          $product_table .= '<td>'.$product['product_id'].'</td>';
			          $product_table .= '<td class="text-left">'.$product['name'].'</td>';
			          $product_table .= '<td>'.$product['sku'].'</td>';
			          $product_table .= '<td>'.$product['model'].'</td>';
			          $product_table .= '<td>'.$product['price'].'</td>';
			          $product_table .= '<td>'.$product['quantity'].'</td>';
					$product_table .= '</tr>';
				}
				$product_table .= '</tbody>';
				$product_table .= '</table>';
					
				$subject = $this->language->get('text_subject') . date('j F Y, l, H:i');
				
				$message = $this->language->get('text_welcome') . "\n\n";
				$message .= $this->language->get('text_message') . "\n\n";

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setText($message);
				// $mail->send();

				// Pro email Template Mod
				if($this->config->get('pro_email_template_status')){
					$this->load->model('tool/pro_email');

					$email_params = array(
						'type' => 'admin.stock',
						'mail' => $mail,
						'product_table' => $product_table
					);
					
					$this->model_tool_pro_email->generate($email_params);
				}
				else{
					$mail->send();
				}

			} /* if have low stock products */
		}
	}

}
