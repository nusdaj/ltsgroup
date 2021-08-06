<?php
class ControllerExtensionPaymentDbsPaynowQr extends Controller {


	public function index() {
		$this->load->language('extension/payment/dbs_paynow_qr');

		$data['error'] = false;

		$order_id    = $this->session->data['order_id'];
		$order_info  = $this->model_checkout_order->getOrder($order_id);

		$today = date('Y-m-d');

		$order_total = $this->currency->format($order_info['total'], $order_info['currency_code'], '', false);
		$data['qrImgBase64'] = false;
		
		if ($order_info) {

			/* java bridge */
			// require_once("http://localhost:8080/JavaBridge/java/Java.inc");
			// require_once("D:/xampp/tomcat/webapps/JavaBridge/java/Java.inc");
			require_once(DIR_SYSTEM . "library/java/Java.inc");
	 		/* 
		 		String merchantCategoryCode, 
		 		String txnCurrency, 
		 		String countryCode, 
		 		String merchantName, 
		 		String merchantCity, 
		 		String globalUniqueID, 
		 		String proxyType, 
		 		String proxyValue, 
		 		String editableAmountInd, 
		 		String expiryDate
	 		*/
			$qrPayNowObj = new Java("com.dbs.sgqr.generator.io.PayNow",
				"0000",  /* fixed value */
				"702",  /* fixed SGD */
				"SG", /* fixed SG */
				$this->config->get('dbs_paynow_qr_merchant_name'), /* allow key in (need to be agreed by merchant and bank */
				"Singapore",  /* fixed value */
				"SG.PAYNOW", /* fixed value */
				"2", // UEN  
				$this->config->get('dbs_paynow_qr_proxy_value'), /* allow key in (need to be agreed by merchant and bank */ 
				'0', /* This is to determine if the consumer scanning the QR can edit/change the amount or you want them to follow your amount . Looking at the use case, I think it should be “0” which means consumer scanning cannot change the amount */ 
				date('Ymd',strtotime($today . ' + 1 day'))
			);

			$qrPayNowObj->setPayloadFormatInd("01");
			$sgqrObj = $qrPayNowObj;
			$QRDimensions = new Java("com.dbs.sgqr.generator.io.QRDimensions");
			$qrCodeGenerator = new Java("com.dbs.sgqr.generator.QRGeneratorImpl");
			$QRType = new Java("com.dbs.sgqr.generator.io.QRType");
			$PAY_NOW = $QRType->PAY_NOW;
			$qrCodeResponse = $qrCodeGenerator->generateSGQR($PAY_NOW, $sgqrObj, $QRDimensions);
			$qrString = java_values($qrCodeResponse->getSgqrPayload());
			$ImageStreamInBase64Format = java_values($qrCodeResponse->getImageStream());
			// debug($ImageStreamInBase64Format);exit;
			// $data['qr_image_string'] = "00020101021226580009SG.PAYNOW010120213776600489GSG10301004142020033112571852040000530370254074277.715802SG5905XXXXX6009Singapore6210010644234563042162";
			$data['qrImgBase64'] = $ImageStreamInBase64Format;
		}

		$data['timeout_timing'] = 0;
		if ($this->config->get('dbs_paynow_qr_timeout')) {
			$data['timeout_timing'] = $this->config->get('dbs_paynow_qr_timeout');
		}
		$data['cancel_url'] = $this->url->link('checkout/cart');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_paynow_unavailable'] = $this->language->get('text_paynow_unavailable');
		$data['text_time_out'] = $this->language->get('text_time_out');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['instruction'] = nl2br($this->config->get('dbs_paynow_qr_instruction' . $this->config->get('config_language_id')));
		$data['image'] = $this->config->get('paynow_image');

		$data['continue'] = $this->url->link('checkout/success');

		return $this->load->view('extension/payment/dbs_paynow_qr', $data);
	}

	/**
	 * @return string
	 */
	public function checkStatus() {
		// debug($this->session->data['order_id']);exit;
		$json = array();

		if (isset($this->session->data['order_id']) && !empty($this->session->data['order_id'])) {
			$this->load->model('checkout/order');
			$this->load->language('extension/payment/dbs_paynow_qr');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if ($order_info) {
				if ( in_array($order_info['order_status_id'], $this->config->get('config_complete_status') ) ) {
					$json['redirect'] = $this->url->link('checkout/success', '', true);
				}
				if ( in_array($order_info['order_status_id'], $this->config->get('config_cancel_status') ) ){
					$json['redirect'] = $this->url->link('checkout/failure', '', true);
				}
			}

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function checkoutCallback() {

		if (isset($this->request->post['charge'])) {

			if (isset($this->request->get['order_id'])) {
				$this->load->library('dbs_paynow_qr');
				
				$this->load->model('extension/payment/dbs_paynow_qr');
				$this->load->model('checkout/order');

				if ($charge && $charge['status'] == 'successful' ) {
					// Status: processed.
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('dbs_paynow_qr_order_status_id'));
					$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
				} elseif ($charge && $charge['status'] == 'pending') {
					$this->renderWaitingPage();
				} else  {				
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('dbs_paynow_qr_failed_order_status_id'), $charge['failure_message']); // 7 failed status
					$this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
				}
			} else {
				$this->response->redirect($this->url->link('common/home'));
			}
		}
	}


	public function webhook() {
		$event = json_decode(file_get_contents('php://input'), true);
		if (!isset($event['key'])) {
			$this->response->addHeader('HTTP/1.1 400 Bad Request');
			return;
		}

		if ($event['key'] != 'charge.complete') {
			return;
		}

		$this->load->model('extension/payment/dbs_paynow_qr');

		$transaction = $this->model_extension_payment_dbs_paynow_qr->getOrderId($event['data']['id']);
		if (empty($transaction->row)) {
			return;
		}

		$this->request->get['order_id'] = $transaction->row['order_id'];
		return $this->refresh();
	}

	public function processing()
    {
        if (! isset($this->request->get['order_id'])) {
            return;
        }

        if (isset($this->session->data['order_id'])) {
            $backup_order_id = $this->session->data['order_id'];
        }

        // Reuse success logic from OpenCart to cleanup current cart.
        // And checkout/success only works with session->data['order_id'].
        $this->session->data['order_id'] = $this->request->get['order_id'];
        $this->load->controller('checkout/success');
        if (isset($backup_order_id)) {
            $this->session->data['order_id'] = $backup_order_id;
        }

        // But display our page.
        $this->load->language('extension/payment/dbs_paynow_qr_processing');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_processing'),
            'href' => $this->url->link('extension/payment/dbs_paynow_qr/processing')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        if ($this->customer->isLogged()) {
            $data['text_message'] = sprintf(
                $this->language->get('text_customer'),
                $this->url->link('account/account', '', 'SSL'),
                $this->url->link('account/order', '', 'SSL'),
                $this->url->link('account/download', '', 'SSL'),
                $this->url->link('information/contact')
            );
        } else {
            $data['text_message'] = sprintf(
                $this->language->get('text_guest'),
                $this->url->link('information/contact')
            );
        }

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->response->setOutput(
                $this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data)
            );
        } else {
            $this->response->setOutput($this->load->view('common/success.tpl', $data));
        }
    }

     private function renderWaitingPage()
    {
        $dbs_paynow_qr_waiting = 'dbs_paynow_qr_waiting_' . $this->request->get['order_id'];

        if (! isset($this->session->data[$dbs_paynow_qr_waiting])) {
            $this->session->data[$dbs_paynow_qr_waiting] = 1;
        } else {
            $this->session->data[$dbs_paynow_qr_waiting]++;
            if ($this->session->data[$dbs_paynow_qr_waiting] > 5) {
                $this->response->redirect(
                    $this->url->link('extension/payment/dbs_paynow_qr/processing', 'order_id=' . $this->request->get['order_id'])
                );
                return;
            }
        }

        $this->load->language('checkout/success');
        $this->load->language('extension/payment/dbs_paynow_qr_waiting');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_basket'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_checkout'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_waiting'),
            'href' => $this->url->link('extension/payment/dbs_paynow_qr/checkoutcallback', 'order_id=' . $this->request->get['order_id'])
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_message'] = $this->language->get('text_message');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/dbs_paynow_qr_waiting.tpl')) {
            $this->response->setOutput(
                $this->load->view($this->config->get('config_template') . '/template/extension/payment/dbs_paynow_qr_waiting.tpl', $data)
            );
        } else {
            $this->response->setOutput($this->load->view('extension/payment/dbs_paynow_qr_waiting.tpl', $data));
        }
    }
}