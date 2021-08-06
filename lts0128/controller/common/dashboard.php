<?php
class ControllerCommonDashboard extends Controller {
	public function index() {
		
		try {
			$operation = ', dashboard module';
			$this->load->controller(
				'facebook/facebookproduct/validateFAEAndCatalogSetupAndProductUploadComplete',
				$operation);
		} catch (Exception $e) {
		$data['error_facebook_sync'] =
			($e->getCode() === FacebookGraphAPIError::ACCESS_TOKEN_EXCEPTION_CODE)
			? $this->language->get('text_warning_facebook_access_token_problem')
			: $e->getMessage();
		}

		$this->load->language('common/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}
		// Check CRON job for low stock notification
		if ($this->config->get('config_low_stock_notify')) {
			$data['warning_cron_stock'] = nl2br($this->language->get('warning_cron_stock'));
		} else {
			$data['warning_cron_stock'] = '';
		}

		// Dashboard Extensions
		$dashboards = array();

		$this->load->model('extension/extension');

		// Get a list of installed modules
		$extensions = $this->model_extension_extension->getInstalled('dashboard');
		
		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $code) {
			if ($this->config->get('dashboard_' . $code . '_status') && $this->user->hasPermission('access', 'extension/dashboard/' . $code)) {
				$output = $this->load->controller('extension/dashboard/' . $code . '/dashboard');
				
				if ($output) {
					$dashboards[] = array(
						'code'       => $code,
						'width'      => $this->config->get('dashboard_' . $code . '_width'),
						'sort_order' => $this->config->get('dashboard_' . $code . '_sort_order'),
						'output'     => $output
					);
				}
			}
		}

		$sort_order = array();

		foreach ($dashboards as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $dashboards);
		
		// Split the array so the columns width is not more than 12 on each row.
		$width = 0;
		$column = array();
		$data['rows'] = array();
		
		foreach ($dashboards as $dashboard) {
			$column[] = $dashboard;
			
			$width = ($width + $dashboard['width']);
			
			if ($width >= 12) {
				$data['rows'][] = $column;
				
				$width = 0;
				$column = array();
			}
		}

		$data = $this->load->controller('common/common', $data);
		$data['newspanel'] = $this->load->controller('common/newspanel');

		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}

		$this->response->setOutput($this->load->view('common/dashboard', $data));
	}
}