<?php
class ControllerInformationInformation extends Controller {
	public function index() {
		$this->load->language('information/information');

		$this->load->model('catalog/information');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
	
		$information_id = 0;
	
		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		}

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			
			$this->document->setTitle($information_info['meta_title']);
			$this->document->setDescription($information_info['meta_description']);
			$this->document->setKeywords($information_info['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $information_info['title'],
				'href' => $this->url->link('information/information', 'information_id=' .  $information_id)
			);

			$data['heading_title'] = $information_info['title'];

			$data['button_continue'] = $this->language->get('button_continue');

			$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if ($information_id == 4) {
				// All about us here
				$oc = $this;
				$language_id = $this->config->get('config_language_id');
				$modulename  = 'aboutus';
			    $this->load->library('modulehelper');
			    $Modulehelper = Modulehelper::get_instance($this->registry);
				$data['icon1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'icon1');
				$data['ititle1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'ititle1');
				$data['iicon1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'iicon1');
				$data['icon2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'icon2');
				$data['ititle2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'ititle2');
				$data['iicon2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'iicon2');
				$data['icon3'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'icon3' );
				$data['ititle3'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'ititle3');
				$data['iicon3'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'iicon3');
				$data['icon4'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'icon4');
				$data['ititle4'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'ititle4');
				$data['iicon4'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'iicon4');
				$data['icon5'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'icon5' );
				$data['ititle5'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'ititle5');
				$data['iicon5'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'iicon5');
				$data['title1'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title1');
				$data['description1'] = html_entity_decode($Modulehelper->get_field ( $oc, $modulename, $language_id, 'description1'));
				$data['title2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title2');
				$data['description2'] = html_entity_decode($Modulehelper->get_field ( $oc, $modulename, $language_id, 'description2'));
				$data['title3'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title3');
				$data['description3'] = html_entity_decode($Modulehelper->get_field ( $oc, $modulename, $language_id, 'description3'));
				$data['services'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'services');
				$data['client'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'client');			

				$this->response->setOutput($this->load->view('information/aboutus', $data));
			} else {
				$this->response->setOutput($this->load->view('information/information', $data));
			}
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('information/information', 'information_id=' . $information_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function agree() {
		$this->load->model('catalog/information');

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$output = '';

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
		}

		$this->response->setOutput($output);
	}
}