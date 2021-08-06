<?php
class ControllerExtensionPaymentOmise extends Controller {
	/**
	 * @return string
	 */
	private function searchErrorTranslation($clue) {
	    $this->load->language('extension/payment/omise');

	    $translate_code = 'error_' . str_replace(' ', '_', strtolower($clue));
	    $translate_msg  = $this->language->get($translate_code);

	    if ($translate_code !== $translate_msg)
	        return $translate_msg;

	    return $clue;
	}

	public function checkoutCallback() {
		if (isset($this->request->get['order_id'])) {
			$this->load->library('omise');
			
			$this->load->model('extension/payment/omise');
			$this->load->model('checkout/order');

			$order_id    = $this->request->get['order_id'];
			$omise_keys  = $this->model_extension_payment_omise->retrieveOmiseKeys();
			$transaction = $this->model_extension_payment_omise->getChargeTransaction($this->request->get['order_id']);

			$charge = OmiseCharge::retrieve($transaction->row['omise_charge_id'], $omise_keys['pkey'], $omise_keys['skey']); 

			//if ($charge && $charge['authorized'] && $charge['captured']) {
			if ($charge && $charge['authorized'] && $charge['capture']) {
				// Status: processed.
				$this->model_checkout_order->addOrderHistory($order_id, 15);
				$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
			} elseif ($charge && $charge['status'] == 'pending') {
				$this->renderWaitingPage();
			} else {
				// Status: failed.
				//$this->model_checkout_order->addOrderHistory($order_id, 10, $charge['failure_message']);
				$this->model_checkout_order->addOrderHistory($order_id, 7, $charge['failure_message']); // 7 failed status
				$this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
			}
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}

	/**
	 * Checkout orders and charge a card process
	 * @return string(Json)
	 */
	public function checkout() {
		if (isset($this->request->post['omise_token'])) {
			$this->load->library('omise');
			
			$this->load->model('extension/payment/omise');
			$this->load->model('checkout/order');

			// Get Omise configuration.
			$omise = array();
			if ($this->config->get('omise_test_mode')) {
				$omise['pkey'] = $this->config->get('omise_pkey_test');
				$omise['skey'] = $this->config->get('omise_skey_test');
			} else {
				$omise['pkey'] = $this->config->get('omise_pkey');
				$omise['skey'] = $this->config->get('omise_skey');
			}

			// Create a order history with `Processing` status
			$order_id    = $this->session->data['order_id'];
			$order_info  = $this->model_checkout_order->getOrder($order_id);
			$order_total = $this->currency->format($order_info['total'], $order_info['currency_code'], '', false);
			if ($order_info) {
				try {
					// Try to create a charge and capture it.
					$omise_charge = OmiseCharge::create(
						array(
							"amount"      => OmisePluginHelperCharge::amount($order_info['currency_code'], $order_total),
							"currency"    =>$this->config->get('config_currency'),
							"description" => $this->request->post['description'],
							"return_uri"  => $this->url->link('extension/payment/omise/checkoutcallback&order_id='.$order_id, '', 'SSL'),
							"card"        => $this->request->post['omise_token'],
							"capture"     => $this->config->get('omise_auto_capture')
						),
						$omise['pkey'],
						$omise['skey']
					);

					// Status: failed.
					if ($omise_charge['failure_code'] || $omise_charge['failure_code']) {
						throw new Exception($omise_charge['failure_code'].': '.$omise_charge['failure_code'], 1);
					}

					$this->model_extension_payment_omise->addChargeTransaction($order_id, $omise_charge['id']);

					if ($this->config->get('omise_3ds')) {
						// Status: processing.
						$this->model_checkout_order->addOrderHistory($order_id, 2);

						echo json_encode(array(
							'omise'    => $omise_charge,
							'captured' => $omise_charge['captured'],
							'redirect' => $omise_charge['authorize_uri']
						));
					} else {
						//if ($omise_charge['authorized'] && $omise_charge['captured']) {
						if ($omise_charge['authorized'] && $omise_charge['capture']) {
							// Status: processed.
							$this->model_checkout_order->addOrderHistory($order_id, 15);
						} else if ($omise_charge['authorized']) {
							// Status: processing.
							$this->model_checkout_order->addOrderHistory($order_id, 2);
						} else {
							// Status: failed.
							throw new Exception('Your charge was failed - '.$omise_charge['failure_code'].': '.$omise_charge['failure_code'], 1);
						}

						echo json_encode(array(
							'omise'    => $omise_charge,
							'captured' => $omise_charge['captured']
						));
					}
				} catch (Exception $e) {
					// Status: failed.
					$error_message = $this->searchErrorTranslation('Payment ' . $e->getMessage());

					//$this->model_checkout_order->addOrderHistory($order_id, 10, $error_message);
					$this->model_checkout_order->addOrderHistory($order_id, 7, $error_message);
					echo json_encode(array(
						'error' => $error_message
					));
				}
			} else {
				echo json_encode(array('error' => 'Cannot find your order, please try again.'));
			}
		} else {
			return 'not authorized';
		}
	}

	/**
	 * Retrieve list of months translation
	 *
	 * @return array
	 */
	public function getMonths() {
		$months = array();
		for ($index=1; $index <= 12; $index++) {
			$month = ($index < 10) ? '0'.$index : $index;
			$months[$month] = $month;
		}
		return $months;
	}

	/**
	 * Retrieve array of available years
	 *
	 * @return array
	 */
	public function getYears() {
		$years = array();
		$first = date("Y");

		for ($index=0; $index <= 10; $index++) {
			$year = $first + $index;
			$years[$year] = $year;
		}
		return $years;
	}

	/**
	 * Omise card information form
	 * @return void
	 */
	public function index() {
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/omise');
		$this->load->language('extension/payment/omise');

		$data = array();

		// Retrieve order information
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if ($order_info) {
			$data = array_merge($data, array(
				'button_confirm'   => $this->language->get('button_confirm'),
				'checkout_url'     => $this->url->link('extension/payment/omise/checkout', '', 'SSL'),
				'success_url'      => $this->url->link('checkout/success', '', 'SSL'),
				'text_config_one'  => trim($this->config->get('text_config_one')),
				'text_config_two'  => trim($this->config->get('text_config_two')),
				'orderid'          => date('His') . $this->session->data['order_id'],
				'callbackurl'      => $this->url->link('payment/custom/callback', '', 'SSL'),
				'orderdate'        => date('YmdHis'),
				'currency'         => $order_info['currency_code'],
				'orderamount'      => $this->currency->format($order_info['total'], $order_info['currency_code'] , false, false),
				'billemail'        => $order_info['email'],
				'billphone'        => html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8'),
				'billaddress'      => html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8'),
				'billcountry'      => html_entity_decode($order_info['payment_iso_code_2'], ENT_QUOTES, 'UTF-8'),
				'billprovince'     => html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8'),
				'billcity'         => html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8'),
				'billpost'         => html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8'),
				'deliveryname'     => html_entity_decode($order_info['shipping_firstname'] . $order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8'),
				'deliveryaddress'  => html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8'),
				'deliverycity'     => html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8'),
				'deliverycountry'  => html_entity_decode($order_info['shipping_iso_code_2'], ENT_QUOTES, 'UTF-8'),
				'deliveryprovince' => html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8'),
				'deliveryemail'    => $order_info['email'],
				'deliveryphone'    => html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8'),
				'deliverypost'     => html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8'),
				'loop_months'      => $this->getMonths(),
				'loop_years'       => $this->getYears(),
				'omise'            => $this->model_extension_payment_omise->retrieveOmiseKeys()
			));

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/omise.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/extension/payment/omise.tpl', $data);
			} else {
				return $this->load->view('extension/payment/omise.tpl', $data);
			}
		}
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
        $this->load->language('extension/payment/omise_processing');
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
            'href' => $this->url->link('extension/payment/omise/processing')
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

	public function webhook() {
		$event = json_decode(file_get_contents('php://input'), true);
		if (!isset($event['key'])) {
			$this->response->addHeader('HTTP/1.1 400 Bad Request');
			return;
		}

		if ($event['key'] != 'charge.complete') {
			return;
		}

		$this->load->model('extension/payment/omise');
		$transaction = $this->model_extension_payment_omise->getOrderId($event['data']['id']);
		if (empty($transaction->row)) {
			return;
		}

		$this->request->get['order_id'] = $transaction->row['order_id'];
		return $this->refresh();
	}

	public function refresh() {
		try {
			if (! isset($this->request->get['order_id'])) {
				throw new Exception('order_id is required');
			}

			$this->load->model('checkout/order');


			/* commented because order status 2 at checkout/order will trigger email even when customer have not make payment */
			// $order = $this->model_checkout_order->getOrder($this->request->get['order_id']);
			// if ($order['order_status_id'] != 2) {
			// 	throw new Exception('Order status MUST be `Processing` to be updated.');
			// }
			$order = $this->model_checkout_order->getOrder($this->request->get['order_id']);
			if ($order['order_status_id'] != 0) {
				throw new Exception('Order status MUST be `Processing` to be updated.');
			}

			$this->load->model('extension/payment/omise');
			$this->load->library('omise');
			

			$transaction = $this->model_extension_payment_omise->getChargeTransaction($this->request->get['order_id']);
			if (empty($transaction->row)) {
				throw new Exception('Order not found');
			}

			$omise_keys = $this->model_extension_payment_omise->retrieveOmiseKeys();
			$charge     = OmiseCharge::retrieve(
				$transaction->row['omise_charge_id'],
				$omise_keys['pkey'],
				$omise_keys['skey']
			);

			// $this->log->write($charge);

			//if ($charge && $charge['authorized'] && $charge['captured']) {
			if ($charge && $charge['authorized'] && $charge['capture']) {
				// Status: processed.
				$this->model_checkout_order->addOrderHistory($this->request->get['order_id'], 17);
				$new_order = $this->model_checkout_order->getOrder($this->request->get['order_id']);
			} else {
				// Status: failed.
				$this->model_checkout_order->addOrderHistory(
					$this->request->get['order_id'],
					7,
					$charge['failure_message']
				);
				$new_order = $this->model_checkout_order->getOrder($this->request->get['order_id']);
			}

			$output = array(
				'success'       => 'Order status has been updated.',
				'new_status'    => $new_order['order_status'],
				'new_status_id' => $new_order['order_status_id'],
			);
		} catch (Exception $e) {
			$output = array('error' => $e->getMessage());
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($output));
	}

    private function renderWaitingPage()
    {
        $omise_waiting = 'omise_waiting_' . $this->request->get['order_id'];

        if (! isset($this->session->data[$omise_waiting])) {
            $this->session->data[$omise_waiting] = 1;
        } else {
            $this->session->data[$omise_waiting]++;
            if ($this->session->data[$omise_waiting] > 5) {
                $this->response->redirect(
                    $this->url->link('extension/payment/omise/processing', 'order_id=' . $this->request->get['order_id'])
                );
                return;
            }
        }

        $this->load->language('checkout/success');
        $this->load->language('extension/payment/omise_waiting');
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
            'href' => $this->url->link('extension/payment/omise/checkoutcallback', 'order_id=' . $this->request->get['order_id'])
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_message'] = $this->language->get('text_message');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/omise_waiting.tpl')) {
            $this->response->setOutput(
                $this->load->view($this->config->get('config_template') . '/template/extension/payment/omise_waiting.tpl', $data)
            );
        } else {
            $this->response->setOutput($this->load->view('extension/payment/omise_waiting.tpl', $data));
        }
    }
}

