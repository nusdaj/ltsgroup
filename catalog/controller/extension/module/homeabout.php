<?php
class ControllerExtensionModuleHomeabout extends Controller {
	public function index() {
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'homeabout';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);
		$data['title'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title');
		$data['subtitle'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'subtitle');
		$data['services'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'services');

		$data['href'] = $this->url->link('information/information', 'information_id=4');

		return $this->load->view('extension/module/homeabout', $data);
	}
}