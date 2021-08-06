<?php
class ControllerExtensionPaymentPaynow extends Controller {
	public function index() {
		$this->load->language('extension/payment/paynow');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_description'] = $this->language->get('text_description');
		//$data['text_payment'] = $this->language->get('text_payment');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['bank'] = nl2br($this->config->get('paynow_bank' . $this->config->get('config_language_id')));
		$data['text_payment'] = nl2br($this->config->get('paynow_extra_note' . $this->config->get('config_language_id')));
		$data['image'] = $this->config->get('paynow_image');

		$data['continue'] = $this->url->link('checkout/success');

		return $this->load->view('extension/payment/paynow', $data);
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'paynow') {
			$this->load->language('extension/payment/paynow');

			$this->load->model('checkout/order');

			$comment  = $this->language->get('text_instruction') . "\n\n";
			$comment .= $this->config->get('paynow_bank' . $this->config->get('config_language_id')) . "\n\n";
			$comment .= "<img src='".HTTPS_SERVER.'image/'.$this->config->get('paynow_image') . "' />\n\n";;
			$comment .= $this->config->get('paynow_extra_note' . $this->config->get('config_language_id')) . "\n\n";
			//$comment .= $this->language->get('text_payment'). "\n\n";

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('paynow_order_status_id'), $comment, true);
		}
	}
}