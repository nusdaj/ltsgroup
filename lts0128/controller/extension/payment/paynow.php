<?php
class ControllerExtensionPaymentPaynow extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/paynow');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paynow', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_bank'] = $this->language->get('entry_bank');
		$data['entry_extra_note'] = $this->language->get('entry_extra_note');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['bank' . $language['language_id']])) {
				$data['error_bank' . $language['language_id']] = $this->error['bank' . $language['language_id']];
			} else {
				$data['error_bank' . $language['language_id']] = '';
			}
		}
                
                if(isset($this->error['image'])) {
                    $data['error_image'] = $this->error['image'];
                }else{
                    $data['error_image'] = '';
                }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/paynow', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/paynow', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		$this->load->model('localisation/language');

		foreach ($languages as $language) {
			if (isset($this->request->post['paynow_bank' . $language['language_id']])) {
				$data['paynow_bank' . $language['language_id']] = $this->request->post['paynow_bank' . $language['language_id']];
			} else {
				$data['paynow_bank' . $language['language_id']] = $this->config->get('paynow_bank' . $language['language_id']);
			}
			
			if (isset($this->request->post['paynow_extra_note' . $language['language_id']])) {
				$data['paynow_extra_note' . $language['language_id']] = $this->request->post['paynow_extra_note' . $language['language_id']];
			} else {
				$data['paynow_extra_note' . $language['language_id']] = $this->config->get('paynow_extra_note' . $language['language_id']);
			}
		}

		$data['languages'] = $languages;

		if (isset($this->request->post['paynow_total'])) {
			$data['paynow_total'] = $this->request->post['paynow_total'];
		} else {
			$data['paynow_total'] = $this->config->get('paynow_total');
		}

		if (isset($this->request->post['paynow_order_status_id'])) {
			$data['paynow_order_status_id'] = $this->request->post['paynow_order_status_id'];
		} else {
			$data['paynow_order_status_id'] = $this->config->get('paynow_order_status_id');
		}
                $this->load->model('tool/image');
		if ($this->config->get('paynow_image') != "" && file_exists(DIR_IMAGE . $this->config->get('paynow_image'))){
			$data['thumb'] = $this->model_tool_image->resize($this->config->get('paynow_image'), 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
                
		if (isset($this->request->post['paynow_image'])) {
			$data['paynow_image'] = $this->request->post['paynow_image'];
		} else {
			$data['paynow_image'] = $this->config->get('paynow_image');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paynow_geo_zone_id'])) {
			$data['paynow_geo_zone_id'] = $this->request->post['paynow_geo_zone_id'];
		} else {
			$data['paynow_geo_zone_id'] = $this->config->get('paynow_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paynow_status'])) {
			$data['paynow_status'] = $this->request->post['paynow_status'];
		} else {
			$data['paynow_status'] = $this->config->get('paynow_status');
		}

		if (isset($this->request->post['paynow_sort_order'])) {
			$data['paynow_sort_order'] = $this->request->post['paynow_sort_order'];
		} else {
			$data['paynow_sort_order'] = $this->config->get('paynow_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paynow', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paynow')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['paynow_bank' . $language['language_id']])) {
				$this->error['bank' .  $language['language_id']] = $this->language->get('error_bank');
			}
		}
//                if (empty($this->request->post['paynow_image'])) {
//                        $this->error['image'] = $this->language->get('error_image');
//                }
                
		return !$this->error;
	}
}