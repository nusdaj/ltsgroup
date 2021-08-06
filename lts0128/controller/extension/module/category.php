<?php
class ControllerExtensionModuleCategory extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('category', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_text_status'] = $this->language->get('entry_text_status');
		$data['entry_price_status'] = $this->language->get('entry_price_status');
		$data['entry_category_status'] = $this->language->get('entry_category_status');
		$data['entry_brand_status'] = $this->language->get('entry_brand_status');
		$data['entry_length_status'] = $this->language->get('entry_length_status');
		$data['entry_filter_status'] = $this->language->get('entry_filter_status');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/category', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/category', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['category_status'])) {
			$data['category_status'] = $this->request->post['category_status'];
		} else {
			$data['category_status'] = $this->config->get('category_status');
		}

		if (isset($this->request->post['category_ctgrs_status'])) {
			$data['category_ctgrs_status'] = $this->request->post['category_ctgrs_status'];
		} else {
			$data['category_ctgrs_status'] = $this->config->get('category_ctgrs_status');
		}

		if (isset($this->request->post['category_brand_status'])) {
			$data['category_brand_status'] = $this->request->post['category_brand_status'];
		} else {
			$data['category_brand_status'] = $this->config->get('category_brand_status');
		}

	    if (isset($this->request->post['category_length_status'])) {
	      	$data['category_length_status'] = $this->request->post['category_length_status'];
	    } else {
	      	$data['category_length_status'] = $this->config->get('category_length_status');
	    }

		if (isset($this->request->post['category_price_status'])) {
			$data['category_price_status'] = $this->request->post['category_price_status'];
		} else {
			$data['category_price_status'] = $this->config->get('category_price_status');
		}

		if (isset($this->request->post['category_filter_status'])) {
			$data['category_filter_status'] = $this->request->post['category_filter_status'];
		} else {
			$data['category_filter_status'] = $this->config->get('category_filter_status');
		}

		if (isset($this->request->post['category_text_status'])) {
			$data['category_text_status'] = $this->request->post['category_text_status'];
		} else {
			$data['category_text_status'] = $this->config->get('category_text_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/category', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}