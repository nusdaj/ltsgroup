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

			$this->response->redirect($this->url->link('sale/enquirenow', 'token=' . $this->session->data['token'], true));
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

		if (isset($this->request->get['id'])) {
			$id = $this->request->get['id'];
		} else {
			$id = 0;
		}

		$enquiry_info = $this->model_sale_enquirenow->getEnquirenow($id);

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
			$data['text_message_detail'] = $this->language->get('text_message_detail');
			$data['text_id'] = $this->language->get('text_id');
			$data['text_invoice'] = $this->language->get('text_invoice');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_affiliate'] = $this->language->get('text_affiliate');
			$data['text_enquiry'] = sprintf($this->language->get('text_enquiry'), $this->request->get['id']);
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_message'] = $this->language->get('text_message');
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
			$data['text_pricelist'] = $this->language->get('text_pricelist');
			$data['text_printing_cost'] = $this->language->get('text_printing_cost');
			$data['text_product'] = $this->language->get('text_product');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_quantity'] = $this->language->get('text_quantity');

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

			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_generate'] = $this->language->get('button_generate');

			$url = '';

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

			$data['cancel'] = $this->url->link('sale/enquirenow', 'token=' . $this->session->data['token'] . $url, true);

			$data['token'] = $this->session->data['token'];

			// AJ Aug 16: pass Enquire Now data to the view template.
			$data['id'] = $id;
			$data['name'] = $enquiry_info['name'];
			$data['email'] = $enquiry_info['email'];
			$data['telephone'] = $enquiry_info['telephone'];
			$data['product'] = $enquiry_info['product_name'];
			$data['message'] = nl2br($enquiry_info['message']);
			$data['product_id'] = $enquiry_info['product_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($enquiry_info['date_added']));
			$data['product_link'] = str_replace(HTTPS_SERVER, HTTPS_CATALOG, $this->url->link('product/product&product_id=' . $enquiry_info['product_id']));

			// AJ Aug 16: get full price list of the product, as well as printing cost, if any.
			$this->load->model('catalog/product');
			$data['pricelist'] = array();

			$product = $this->model_catalog_product->getProduct($enquiry_info['product_id']);
			$data['pricelist'][$product['minimum']] = round((float)$product['price'],2);

			$discounts = $this->model_catalog_product->getProductDiscounts($enquiry_info['product_id']);
			foreach ($discounts as $discount) {
				$data['pricelist'][$discount['quantity']] = round((float)$discount['price'],2);
			}

			// AJ Aug 17: additional tabs for storing and showing printing cost.
			$data['tabs'] = array();
			$cat_id = 100; // Hard-coded, category name "Logo Printing"
			$products = $this->model_catalog_product->getProductsByCategoryId($cat_id);

			foreach ($products as $product) {
				$product = $this->model_catalog_product->getProduct($product['product_id']);
				$discounts = $this->model_catalog_product->getProductDiscounts($product['product_id']);

				$content = '<h3>' . $product['name'] . '</h3>';
				$content = $content . '<table class="table table-bordered"><thead><tr><td>' . $data['text_quantity'] . '</td>';
				$content = $content . '<td>' . $product['minimum'] . '</td>';
				foreach ($discounts as $discount) {
					$content = $content . '<td>' . $discount['quantity'] . '</td>';
				}
				$content = $content . '</tr></thead><tbody><tr><td>' . $data['text_price'] . '</td>';
				$content = $content . '<td>' . round($product['price'],2) . '</td>';
				foreach ($discounts as $discount) {
					$content = $content . '<td>' . round((float)$discount['price'],2) . '</td>';
				}
				$content = $content . '</tr></tbody></table>';				

				$data['tabs'][] = array(
					'code' => $product['product_id'],
					'title' => substr($product['name'],0,12),
					'content' => $content
				);
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