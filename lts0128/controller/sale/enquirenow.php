<?php
class ControllerSaleEnquirenow extends Controller {
	public function index() {
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/enquirenow', $data));
	}
}
