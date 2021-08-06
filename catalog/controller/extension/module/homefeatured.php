<?php
class ControllerExtensionModuleHomefeatured extends Controller {
	public function index() {
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'homefeatured';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);
		$data['title'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title');
		$data['category'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'category');

		return $this->load->view('extension/module/homefeatured', $data);
	}
}