<?php
// AJ Aug 15: copied from enquiry module.
class ControllerSaleEnquirenow extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/enquirenow');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/enquirenow');

		$this->getList();
	}
	
	public function delete() {
		$this->load->language('sale/enquirenow');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/enquirenow');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_sale_enquirenow->deleteEnquirenow($id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('sale/enquiry', 'token=' . $this->session->data['token'], true));
		}

		$this->getList();
	}
	
	protected function getList() {
		// AJ Aug 15: pagination & data loading
		$url = '';
		if (isset($this->request->get['page'])) {
			$page = (int) $this->request->get['page'];
			$url .= '&page=' . $this->request->get['page'];
		} else {
			$page = 1; 
		}

		// AJ Aug 15: the breadcrumbs at the top left navigation
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/enquirenow', 'token=' . $this->session->data['token'] . $url, true)
		);

		// AJ Aug 15: the 5 buttons at the top right corner
		// AJ Aug 16: the first 4 buttons will be disabled all the time.
		$data['invoice'] = $this->url->link('sale/enquirenow/invoice', 'token=' . $this->session->data['token'], true);
		$data['shipping'] = $this->url->link('sale/enquirenow/shipping', 'token=' . $this->session->data['token'], true);
		$data['pickpacklist'] = $this->url->link('sale/enquirenow/pickPackList', 'token=' . $this->session->data['token'], true);
		$data['add'] = $this->url->link('sale/enquirenow/add', 'token=' . $this->session->data['token'], true);
		$data['delete'] = $this->url->link('sale/enquirenow/delete', 'token=' . $this->session->data['token'], true);

		// AJ Aug 15: pagination & data loading
		$data['enquirenows'] = array();

		$start  = ($page - 1) * $this->config->get('config_limit_admin');
		$limit  = $this->config->get('config_limit_admin');

		$results = $this->model_sale_enquirenow->getAllEnquirenow($start, $limit);

		foreach ($results as $result) {
			$data['enquirenows'][] = array(
				'id'      		=> $result['id'],
				'name'          => $result['name'],
				'email' 	    => $result['email'],
				'telephone'     => $result['telephone'],
				'message'		=> $result['message'],
				'product'		=> $result['product_name'],
				'product_id'	=> $result['product_id'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'          => $this->url->link('sale/enquirenow/info', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, true),
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_id'] = $this->language->get('column_id');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_telephone'] = $this->language->get('column_telephone');
		$data['column_message'] = $this->language->get('column_message');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_id'] = $this->language->get('entry_id');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['text_pickpacklist'] = $this->language->get('text_pickpacklist');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$enquiry_total = $this->model_sale_enquirenow->getNumEnquirenow();
		$pagination = new Pagination();
		$pagination->total = $enquiry_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/enquirenow', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($enquiry_total) ? (((int)$page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($enquiry_total - $this->config->get('config_limit_admin'))) ? $enquiry_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $enquiry_total, ceil($enquiry_total / $this->config->get('config_limit_admin')));
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/enquirenow', $data));
	}

	public function info() {

		$this->load->model('sale/enquirenow');

		if (isset($this->request->get['enquiry_order_id'])) {
			$enquiry_order_id = $this->request->get['enquiry_order_id'];
		} else {
			$enquiry_order_id = 0;
		}

		$enquiry_info = $this->model_sale_enquiry->getEnquiry($enquiry_order_id);

		if ($enquiry_info) { 
			$this->load->language('sale/enquirenow');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
			$data['text_enquiry_detail'] = $this->language->get('text_enquiry_detail');
			$data['text_customer_detail'] = $this->language->get('text_customer_detail');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_store'] = $this->language->get('text_store');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_customer'] = $this->language->get('text_customer');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_telephone'] = $this->language->get('text_telephone');
			$data['text_invoice'] = $this->language->get('text_invoice');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_affiliate'] = $this->language->get('text_affiliate');
			$data['text_enquiry'] = sprintf($this->language->get('text_enquiry'), $this->request->get['enquiry_order_id']);
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_account_custom_field'] = $this->language->get('text_account_custom_field');
			$data['text_payment_custom_field'] = $this->language->get('text_payment_custom_field');
			$data['text_shipping_custom_field'] = $this->language->get('text_shipping_custom_field');
			$data['text_browser'] = $this->language->get('text_browser');
			$data['text_ip'] = $this->language->get('text_ip');
			$data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
			$data['text_user_agent'] = $this->language->get('text_user_agent');
			$data['text_accept_language'] = $this->language->get('text_accept_language');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_history_add'] = $this->language->get('text_history_add');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['column_product'] = $this->language->get('column_product');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');

			$data['entry_enquiry_status'] = $this->language->get('entry_enquiry_status');
			$data['entry_notify'] = $this->language->get('entry_notify');
			$data['entry_override'] = $this->language->get('entry_override');
			$data['entry_comment'] = $this->language->get('entry_comment');

			$data['help_override'] = $this->language->get('help_override');

			$data['button_invoice_print'] = $this->language->get('button_invoice_print');
			$data['button_shipping_print'] = $this->language->get('button_shipping_print');
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_generate'] = $this->language->get('button_generate');
			$data['button_reward_add'] = $this->language->get('button_reward_add');
			$data['button_reward_remove'] = $this->language->get('button_reward_remove');
			$data['button_commission_add'] = $this->language->get('button_commission_add');
			$data['button_commission_remove'] = $this->language->get('button_commission_remove');
			$data['button_history_add'] = $this->language->get('button_history_add');
			$data['button_ip_add'] = $this->language->get('button_ip_add');

			$data['tab_history'] = $this->language->get('tab_history');
			$data['tab_additional'] = $this->language->get('tab_additional');

			$url = '';

			if (isset($this->request->get['filter_enquiry_order_id'])) {
				$url .= '&filter_enquiry_order_id=' . $this->request->get['filter_enquiry_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_enquiry_status'])) {
				$url .= '&filter_enquiry_status=' . $this->request->get['filter_enquiry_status'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['enquiry'])) {
				$url .= '&enquiry=' . $this->request->get['enquiry'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/enquirenow', 'token=' . $this->session->data['token'] . $url, true)
			);

			$data['shipping'] = $this->url->link('sale/enquirenow/shipping', 'token=' . $this->session->data['token'] . '&enquiry_order_id=' . (int)$this->request->get['enquiry_order_id'], true);
			$data['invoice'] = $this->url->link('sale/enquirenow/invoice', 'token=' . $this->session->data['token'] . '&enquiry_order_id=' . (int)$this->request->get['enquiry_order_id'], true);
			$data['edit'] = $this->url->link('sale/enquirenow/edit', 'token=' . $this->session->data['token'] . '&enquiry_order_id=' . (int)$this->request->get['enquiry_order_id'], true);
			$data['cancel'] = $this->url->link('sale/enquirenow', 'token=' . $this->session->data['token'] . $url, true);

			$data['token'] = $this->session->data['token'];

			$data['enquiry_order_id'] = $this->request->get['enquiry_order_id'];

			$data['store_id'] = $enquiry_info['store_id'];
			$data['store_name'] = $enquiry_info['store_name'];
			
			if ($enquiry_info['store_id'] == 0) {
				$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			} else {
				$data['store_url'] = $enquiry_info['store_url'];
			}

			if ($enquiry_info['invoice_no']) {
				$data['invoice_no'] = $enquiry_info['invoice_prefix'] . $enquiry_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($enquiry_info['date_added']));

			$data['firstname'] = $enquiry_info['firstname'];
			$data['lastname'] = $enquiry_info['lastname'];

			if ($enquiry_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $enquiry_info['customer_id'], true);
			} else {
				$data['customer'] = '';
			}

			$this->load->model('customer/customer_group');

			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($enquiry_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $enquiry_info['email'];
			$data['telephone'] = $enquiry_info['telephone'];

			$data['shipping_method'] = $enquiry_info['shipping_method'];
			$data['payment_method'] = $enquiry_info['payment_method'];

			// Payment Address
			if ($enquiry_info['payment_address_format']) {
				$format = $enquiry_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{unit_no}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $enquiry_info['payment_firstname'],
				'lastname'  => $enquiry_info['payment_lastname'],
				'company'   => $enquiry_info['payment_company'],
				'address_1' => $enquiry_info['payment_address_1'],
				'address_2' => $enquiry_info['payment_address_2'],
				'unit_no'	=> $enquiry_info['payment_unit_no']?$enquiry_info['payment_unit_no'].', ':'',
				'city'      => $enquiry_info['payment_city'],
				'postcode'  => $enquiry_info['payment_postcode'],
				'zone'      => $enquiry_info['payment_zone'],
				'zone_code' => $enquiry_info['payment_zone_code'],
				'country'   => $enquiry_info['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Shipping Address
			if ($enquiry_info['shipping_address_format']) {
				$format = $enquiry_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{unit_no}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $enquiry_info['shipping_firstname'],
				'lastname'  => $enquiry_info['shipping_lastname'],
				'company'   => $enquiry_info['shipping_company'],
				'address_1' => $enquiry_info['shipping_address_1'],
				'address_2' => $enquiry_info['shipping_address_2'],
				'unit_no'   => $enquiry_info['shipping_unit_no']?$enquiry_info['shipping_unit_no'].', ':'',
				'city'      => $enquiry_info['shipping_city'],
				'postcode'  => $enquiry_info['shipping_postcode'],
				'zone'      => $enquiry_info['shipping_zone'],
				'zone_code' => $enquiry_info['shipping_zone_code'],
				'country'   => $enquiry_info['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Uploaded files
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->model_sale_enquiry->getEnquiryProducts($this->request->get['enquiry_order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_enquiry->getEnquiryOptions($this->request->get['enquiry_order_id'], $product['enquiry_order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], true)
							);
						}
					}
				}

				$data['products'][] = array(
					'enquiry_order_product_id' => $product['enquiry_order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], true)
				);
			}

			$data['vouchers'] = array();

			$vouchers = $this->model_sale_enquiry->getEnquiryVouchers($this->request->get['enquiry_order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $enquiry_info['currency_code'], $enquiry_info['currency_value']),
					'href'        => $this->url->link('sale/voucher/edit', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], true)
				);
			}

			$data['totals'] = array();

			$totals = $this->model_sale_enquiry->getEnquiryTotals($this->request->get['enquiry_order_id']);

			foreach ($totals as $total) {
				//$this->currency->format($total['value'], $enquiry_info['currency_code'], $enquiry_info['currency_value'])
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => (int)$total['value']
				);
			}

			$data['comment'] = nl2br($enquiry_info['comment']);

			$this->load->model('customer/customer');

			$data['reward'] = $enquiry_info['reward_earn'];

			// Reward received
			$data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($this->request->get['enquiry_order_id']);

			$data['affiliate_firstname'] = $enquiry_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $enquiry_info['affiliate_lastname'];

			if ($enquiry_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $enquiry_info['affiliate_id'], true);
			} else {
				$data['affiliate'] = '';
			}

			$data['commission'] = $this->currency->format($enquiry_info['commission'], $enquiry_info['currency_code'], $enquiry_info['currency_value']);

			$this->load->model('marketing/affiliate');

			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['enquiry_order_id']);

			$this->load->model('localisation/order_status');

			$enquiry_status_info = $this->model_localisation_order_status->getOrderStatus($enquiry_info['enquiry_order_status_id']);

			if ($enquiry_status_info) {
				$data['enquiry_status'] = $enquiry_status_info['name'];
			} else {
				$data['enquiry_status'] = '';
			}

			$data['enquiry_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$data['enquiry_order_status_id'] = $enquiry_info['enquiry_order_status_id'];

			$data['account_custom_field'] = $enquiry_info['custom_field'];

			// Custom Fields
			$this->load->model('customer/custom_field');

			$data['account_custom_fields'] = array();

			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'enquiry' => 'ASC'
			);

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($enquiry_info['custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($enquiry_info['custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($enquiry_info['custom_field'][$custom_field['custom_field_id']])) {
						foreach ($enquiry_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['account_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['account_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $enquiry_info['custom_field'][$custom_field['custom_field_id']]
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($enquiry_info['custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name']
							);
						}
					}
				}
			}

			// Custom fields
			$data['payment_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['payment_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['payment_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $enquiry_info['payment_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}

			// Shipping
			$data['shipping_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['shipping_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['shipping_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}

			$data['ip'] = $enquiry_info['ip'];
			$data['forwarded_ip'] = $enquiry_info['forwarded_ip'];
			$data['user_agent'] = $enquiry_info['user_agent'];
			$data['accept_language'] = $enquiry_info['accept_language'];

			// Additional Tabs
			$data['tabs'] = array();

			if ($this->user->hasPermission('access', 'extension/payment/' . $enquiry_info['payment_code'])) {
				if (is_file(DIR_CATALOG . 'controller/extension/payment/' . $enquiry_info['payment_code'] . '.php')) {
					$content = $this->load->controller('extension/payment/' . $enquiry_info['payment_code'] . '/enquiry');
				} else {
					$content = null;
				}

				if ($content) {
					$this->load->language('extension/payment/' . $enquiry_info['payment_code']);

					$data['tabs'][] = array(
						'code'    => $enquiry_info['payment_code'],
						'title'   => $this->language->get('heading_title'),
						'content' => $content
					);
				}
			}

			$this->load->model('extension/extension');

			$extensions = $this->model_extension_extension->getInstalled('fraud');

			foreach ($extensions as $extension) {
				if ($this->config->get($extension . '_status')) {
					$this->load->language('extension/fraud/' . $extension);

					$content = $this->load->controller('extension/fraud/' . $extension . '/enquiry');

					if ($content) {
						$data['tabs'][] = array(
							'code'    => $extension,
							'title'   => $this->language->get('heading_title'),
							'content' => $content
						);
					}
				}
			}
			
			// The URL we send API requests to
			$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			
			// API login
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info) {
				$data['api_id'] = $api_info['api_id'];
				$data['api_key'] = $api_info['key'];
				$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
			} else {
				$data['api_id'] = '';
				$data['api_key'] = '';
				$data['api_ip'] = '';
			}

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/enquirenow_info', $data));
		} else {
			return new Action('error/not_found');
		}
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'sale/enquirenow')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}