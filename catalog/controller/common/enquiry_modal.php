<?php
/* AJ Aug 9: Separate the enquiry modal and make it an independent module */
/* AJ Apr 15: Known issue: some mobile borwsers don't check validity of input.  */
/* AJ Apr 14: Added POST handler, to process the posted data from 'Featured Products' slick list
   One biggest difference from FirstCom's solution is we don't need to validate the input here.
   Because all validation is done at the browser (client) side  */
class ControllerCommonEnquiryModal extends Controller
{
	/* AJ Apr 14, begin: form POST handler data */
	private $error = array();

	// Add New Post by defining it here
	private $posts = array(
		'name'      =>  '',
		'subject'   =>  '',
		'email'     =>  '',
		'telephone' =>  '',
		'featuredProduct' => '',
		'enquiry'   =>  ''  // This will always be the last and large box
	);

	// Add your post value to ignore in the email body content
	private $disallow_in_message_body = array(
		'var_abc_name'
	);

	public function populateDefaultValue()
	{
		$this->posts['name']        = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
		$this->posts['email']       = $this->customer->getEmail();
		$this->posts['telephone']   = $this->customer->getTelephone();
	}
	/* AJ Apr 14, end: form POST handler data */

	public function index()	{
		/* AJ Apr 14, begin: handler to POST form data */
		/* AJ Aug 11: below continue is useless. It's set in the success page */
		// $data['continue'] = $this->url->link('product/category');

		// AJ Apr 20: must load language file before validation. otherwise, error description will be random.
		$this->load->language('common/enquiry_modal');

		//Form
		// Populate values after customer logged in
		if ($this->customer->isLogged()) {
			$this->populateDefaultValue();
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			//$mail->setFrom($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));

			$mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));

			$message = "";

			foreach ($this->posts as $post_var => $post_default_value) {
				if (!in_array($post_var, $this->disallow_in_message_body)) {
					$message .= $this->language->get('entry_' . $post_var) . ": ";
					$message .= $this->request->post[$post_var] ? $this->request->post[$post_var] : $post_default_value;
					$message .= "<br />";
				}
			}

			$mail->setText($message);
			// $mail->send();

			// Pro email Template Mod
			if ($this->config->get('pro_email_template_status')) {

				$this->load->model('tool/pro_email');

				$email_params = array(
					'type' => 'admin.information.contact',
					'mail' => $mail,
					'reply_to' => $this->request->post['email'],
					'data' => array(
						'enquiry_subject' => html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'),
						'enquiry_telephone' => html_entity_decode($this->request->post['telephone'], ENT_QUOTES, 'UTF-8'),
						'enquiry_name' => html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'),
						'enquiry_mail' => html_entity_decode($this->request->post['email'], ENT_QUOTES, 'UTF-8'),
						'enquiry_product' => html_entity_decode($this->request->post['featuredProduct'], ENT_QUOTES, 'UTF-8'),  // AJ Apr 14: add product name in the email
						'enquiry_message' => html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')
						// 'enquiry_message' => html_entity_decode($message, ENT_QUOTES, 'UTF-8')
					),
				);

				$this->model_tool_pro_email->generate($email_params);
			} else {
				$mail->send();
			}

			// AJ Apr 21: added to carry the validate result to form.
			if ($this->request->server['REQUEST_METHOD'] == 'POST') { 
				$data['validation_failed'] = true;  // POST but validation failed. Need to show the modal window
			} else {
				$data['validation_failed'] = false; 
			}

			$this->response->redirect($this->url->link('product/category/success'));
		}
		/* AJ Apr 14, end: handler to POST form data */

		// AJ Apr 14: set POST action target
		// $data['action'] = $this->url->link('common/home', '', true);
		// AJ Aug 11: set POST action to current page. 
		// https://code.tutsplus.com/tutorials/how-to-rewrite-custom-urls-in-opencart--cms-25734
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		// $data['action'] = $this->url->link($this->request->get['_route_'], '', true);
		$data['action'] = $actual_link;
		// echo "<script>alert('" . $actual_link . "')</script>";

		// AJ Apr 20: added to carry the validate result to form.
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data['validation_failed'] = true;  // POST but validation failed. Need to show the modal window

			// AJ Apr 20: reset error 'messages'
			foreach ($this->posts as $post_var => $post_default_value) {
				// Post Value
				if (isset($this->request->post[$post_var])) {
					$data[$post_var] = $this->request->post[$post_var];
				}

				// Error Value
				if (isset($this->error[$post_var])) {
					$data['error_' . $post_var] = $this->error[$post_var];
				}
			}
		} else {
			$data['validation_failed'] = false;

			// AJ Apr 12: load in language file
			$data = array_merge($data, $this->load->language('common/home'));

			foreach ($this->posts as $post_var => $post_default_value) {
				// reset error message
				$data[$post_var] = $post_default_value;
				$data['error_' . $post_var] = '';

				// Label Value
				$data['entry_' . $post_var] = $this->language->get('entry_' . $post_var);
			}
		}

		// AJ Apr 19, copied from contact.php: Captcha
		$data['captcha'] = '';
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
		}

		/* AJ Aug 10: This is the key to enable the call to "echo $enquiry_modal;" in home.tpl and other possible views */
		return $this->load->view('common/enquiry_modal', $data);
	}

	protected function validate()
	{
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		// AJ Apr 20: hidden input, no need to validate. Besides, our setting is too long already.
		// if ((utf8_strlen($this->request->post['subject']) < 3) || (utf8_strlen($this->request->post['subject']) > 32)) {
		// 	$this->error['subject'] = $this->language->get('error_subject');
		// }

		if ((int)$this->request->post['telephone'] < 1) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 300)) {
			$this->error['enquiry'] = $this->language->get('error_enquiry');
		}

		// Captcha
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
		}

		return !$this->error;
	}
}
