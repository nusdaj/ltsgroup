<?php
class ControllerProductProduct extends Controller {
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

    public function populateDefaultValue(){
        $this->posts['name']        = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
        $this->posts['email']       = $this->customer->getEmail();
        $this->posts['telephone']   = $this->customer->getTelephone();
    }

	public function index() {
		// test branches 

		$this->load->language('product/category');

		// << Related Options / Связанные опции  
		$this->load->language('module/related_options');
		$data['text_ro_clear_options'] 			= $this->language->get('text_ro_clear_options');
		// >> Related Options / Связанные опции

		// to test request

		$this->load->language('product/product');
		
		// << Related Options / Связанные опции  
		$this->load->language('module/related_options');
		$data['text_ro_clear_options'] 			= $this->language->get('text_ro_clear_options');
		// >> Related Options / Связанные опции

		$data['text_share'] = $this->language->get('text_share');

		$data['share_html'] = html($this->config->get('config_addthis'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['waiting_module'] = $this->load->controller('extension/module/waiting_list');

		$this->load->model('catalog/category');

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
			);
		}


		$product_id = 0;

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} 

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		$data['quantityincrementdecrement_status'] = $this->config->get('quantityincrementdecrement_status');

		if ($product_info) {

			// $this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
			// $this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

			$this->document->addStyle('catalog/view/javascript/slick/slick.css');
			$this->document->addScript('catalog/view/javascript/slick/slick-custom.min.js');
			
			$this->facebookcommonutils = new FacebookCommonUtils();
			$params = new DAPixelConfigParams(array(
			'eventName' => 'ViewContent',
			'products' => array($product_info),
			'currency' => $this->currency,
			'currencyCode' => $this->session->data['currency'],
			'hasQuantity' => false));
			$facebook_pixel_event_params_FAE =
			$this->facebookcommonutils->getDAPixelParamsForProducts($params);
			// stores the pixel event params in the session
			$this->request->post['facebook_pixel_event_params_FAE'] =
			addslashes(json_encode($facebook_pixel_event_params_FAE));

			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $product_info['name'],
				'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);

			$this->document->setTitle($product_info['meta_title']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['heading_title'] = $data['product_name'] = $product_info['name'];
			$data['product_name'] = $data['product_name'];

			// Visit From Search / Google
			if( isset($this->request->get['product_id']) && !isset($this->request->get['path']) ){
				$product_id = $this->request->get['product_id']; 
				$categories = $this->model_catalog_product->getProductMainCategories($product_id);

				if($categories){
					$this->load->model('catalog/category');
					$categories = array_reverse($categories);
					$categories = end($categories);
					if(isset($categories['path_id'])){
						$category_info = $this->model_catalog_category->getCategory($categories['path_id']);
						
						if($category_info){
							$data['heading_title'] = $category_info['name'];
						}
					}
				}
			}

			// Visit From Category / Special
			if( isset($this->request->get['product_id']) && isset($this->request->get['path']) && !is_array($this->request->get['path']) ){
				$paths = explode('_', $this->request->get['path']);
				$path_id = end($paths);
				$this->load->model('catalog/category');

				$category_info = $this->model_catalog_category->getCategory($path_id);
				if($category_info){
					$data['heading_title'] = $category_info['name'];
				}
			}

			// << Related Options / Связанные опции 
				if ( !$this->model_module_related_options ) {
					$this->load->model('module/related_options');
				}

					// $this->request->get['product'] - fnt_product_design;
				if ( isset($this->request->get['pid']) ) {
					$ro_product_id = $this->request->get['pid'];
				} elseif ( isset($this->request->get['product_id']) ) {
					$ro_product_id = $this->request->get['product_id'];
				} elseif ( isset($this->request->post['product_id']) ) {
					$ro_product_id = $this->request->post['product_id'];	
				} else {
					$ro_product_id = $this->request->get['product'];
				}

				$data['ro_installed']								= $this->model_module_related_options->installed();
				$data['ro_settings']								= $this->config->get('related_options');
				$data['ro_product_id']							= $ro_product_id;
				$data['text_ro_clear_options'] 			= $this->language->get('text_ro_clear_options');
				$data['entry_stock_control_error']  = $this->language->get('entry_stock_control_error');
				$data['ro_theme_name']							= $this->model_module_related_options->getThemeName();
				$data['ro_data'] 										= $this->model_module_related_options->get_ro_data($ro_product_id, true);

				$data['ros_to_select'] 							= $this->model_module_related_options->getROCombSelectedByDefault($ro_product_id, isset($this->request->get['search']) ? $this->request->get['search'] : '');

				if ( !$this->model_catalog_product ) {
					$this->load->model('catalog/product');
				}
				$ro_product = $this->model_catalog_product->getProduct($ro_product_id);
				$data['ro_product_model'] = empty($ro_product['model']) ? '' : $ro_product['model'];

				if ( $data['ro_installed'] ) {
					$data['ro_scripts'] = $this->model_module_related_options->getProductPageScripts();
				}

				if ( $data['ro_theme_name'] == 'journal2' ) {
					foreach ($data['ro_scripts'] as $ro_script) {
						$this->journal2->minifier->addScript($ro_script);
					}
				} 
				
			// >> Related Options / Связанные опции ]]>


			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
			$data['text_purchase'] = $this->language->get('text_purchase');
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
			$data['text_loading'] = $this->language->get('text_loading');
			$data['text_sale'] = $this->language->get('text_sale');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_enquiry'] = $this->language->get('button_enquiry');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_download'] = $this->language->get('button_download');
			$data['entry_product'] = $this->language->get('entry_product');
			

			$this->load->model('catalog/review');

			// <<OPTIONS IMAGE
            $data['product_title'] = $product_info['name'];
            $data['prod_image'] = $product_info['image'];
            // >>OPTIONS IMAGE
            
			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = 'Reviews';

			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];
			/* completecombo */
			if($this->config->get("offerpage_installed")) {
		        $this->load->model("extension/total/salescombo");
		        $data['available_offers'] = $this->model_extension_total_salescombo->getAvailableOffers($this->request->get['product_id']);
		        $this->load->language("extension/total/salescombo");
		        $data['tab_available_offers'] = $this->language->get("tab_available_offers");
		        $data['text_available_offers'] = $this->language->get("text_available_offers");
	        }
			/* completecombo */
			
			$data['is_enquiry'] = $product_info['price']>0?false:true;

			$data['text_enquiry_item'] = $this->language->get('text_enquiry_item');

			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			
			$data['stock'] = $this->language->get('text_instock');

			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			}

			$data['not_avail'] = false;
			if($product_info['quantity'] <= 0) {
				$data['not_avail'] = true;
			}

			$this->load->model('tool/image');

			$theme = $this->config->get('config_theme');

			$image_popup_width = $this->config->get( $theme . '_image_popup_width');
			$image_popup_height = $this->config->get( $theme . '_image_popup_height');

			$image_thumb_width = $this->config->get( $theme . '_image_thumb_width');
			$image_thumb_height = $this->config->get( $theme . '_image_thumb_height');

			$image_additional_width = $this->config->get( $theme . '_image_additional_width');
			$image_additional_height = $this->config->get( $theme . '_image_additional_height');

			$data['vertical_slider'] = $this->config->get( $theme . '_vertical_thumbnails');
			
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			$data['images'] = array();

			if( is_file(DIR_IMAGE . $product_info['image'])) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($product_info['image'], $image_popup_width, $image_popup_height),
					'thumb' => $this->model_tool_image->resize($product_info['image'], $image_thumb_width, $image_thumb_height)
				);
			}
			else{
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize('no_image.png', $image_popup_width, $image_popup_height),
					'thumb' => $this->model_tool_image->resize('no_image.png', $image_thumb_width, $image_thumb_height)
				);
			}
			

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $image_popup_width, $image_popup_height),
					'thumb' => $this->model_tool_image->resize($result['image'], $image_thumb_width, $image_thumb_height)
				);
			}
			
			$data['additional_images'] = array();

			if( is_file(DIR_IMAGE . $product_info['image'])) {
				$data['additional_images'][] = array(
					'popup' => $this->model_tool_image->resize($product_info['image'], $image_popup_width, $image_popup_height),
					'thumb' => $this->model_tool_image->resize($product_info['image'], $image_additional_width, $image_additional_height)
				);
			}
			else{
				$data['additional_images'][] = array(
					'popup' => $this->model_tool_image->resize('no_image.png', $image_popup_width, $image_popup_height),
					'thumb' => $this->model_tool_image->resize('no_image.png', $image_additional_width, $image_additional_height)
				);
			}

			foreach ($results as $result) {
				$data['additional_images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $image_popup_width, $image_popup_height),
					'thumb' => $this->model_tool_image->resize($result['image'], $image_additional_width, $image_additional_height)
				);
			}

			$data['price'] = false;

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                if ($this->config->get('config_product_decimal_places')) {
					$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
					$data['price'] = $this->currency->format2($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                }
			} 

			$data['special'] = false;

			if ((float)$product_info['special']) {
                if ($this->config->get('config_product_decimal_places')) {
					$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
					$data['special'] = $this->currency->format2($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                }
			} 

			$data['tax'] = false;

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
			} 

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			/* completecombo */
			if($this->config->get("offerpage_installed")) {
		        $this->load->language('offers/salescombopge');
		        $data['text_productpage_knowmore'] = $this->language->get('text_productpage_knowmore');
		        $data['text_productpage_clickhere'] = $this->language->get('text_productpage_clickhere');
		        $data['offerclose'] = $this->language->get('offerclose');
		        $data['text_productpage_isavailable'] = $this->language->get('text_productpage_isavailable');
		        $this->load->model('offers/salescombopge');
		        $data['salescombopgeoffers'] = $this->model_offers_salescombopge->getOfferByProductId($product_id);
				$data['offerpopup'] = $this->load->controller("extension/module/salescombopopup", $data['salescombopgeoffers']['autopopup']);
		        unset($data['salescombopgeoffers']['autopopup']);
	        }
			/* completecombo */

			
			$data['discounts'] = array();

			foreach ($discounts as $discount) {
                if ($this->config->get('config_product_decimal_places')) {
					$data['discounts'][] = array(
						'quantity' => $discount['quantity'],
						'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
					);
                } else {
					$data['discounts'][] = array(
						'quantity' => $discount['quantity'],
						'price'    => $this->currency->format2($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
					);
                }
			}
			
			
			// AJ, Mar 7, begin: Get the lowest & highest prices. and pass it to the view
			$aj_price_lowest = min(min(array_column($discounts, 'price')), $product_info['price']);
			$aj_price_highest = max(max(array_column($discounts, 'price')), $product_info['price']);

			if ($aj_price_highest > 0) {
				if ($this->config->get('config_product_decimal_places')) {
					$data['price_lowest'] = $this->currency->format($aj_price_lowest, $this->session->data['currency']);
					$data['price_highest'] = $this->currency->format($aj_price_highest, $this->session->data['currency']);
				} else {
					$data['price_lowest'] = $this->currency->format2($aj_price_lowest, $this->session->data['currency']);
					$data['price_highest'] = $this->currency->format2($aj_price_highest, $this->session->data['currency']);				
				}
			} else {
				$data['price_lowest'] = false;
				$data['price_highest'] = false;
			}	
			// AJ, Mar 7, end			
			

			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					// if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
			                if ($this->config->get('config_product_decimal_places')) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
			                } else {
								$price = $this->currency->format2($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
			                }
						} else {
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'quantity'                => $option_value['quantity'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					// }
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}

			$data['minimum'] = 1;
			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} 

			$data['review_status'] = $this->config->get('config_review_status');

			$data['review_guest'] = false;
			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;

				$data['purchased'] = 1;

				if ($this->config->get('config_review_after_purchase')) {
					$this->load->model('account/order');
					$order_exists = $this->model_account_order->getOrderProductById($product_id);
					if (!$order_exists) {
						$data['purchased'] = 0;
					}
				}
			} 

            $data['sticker'] = $this->load->controller('component/sticker', $product_info['product_id']);
            if ( $product_info['quantity']<=0 ) {
                $data['sticker'] = array(
                    'name' => $this->language->get('text_out_of_stock'),
                    'color' => '#ffffff',
                    'background-color' => '#cdcad2',
                    'image' => ''
                );
            }

            $data['show_special_sticker'] = $data['special'] ? 1 : 0;

			$data['customer_name'] = '';
			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} 

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$data['rating'] = (int)$product_info['rating'];

			// Captcha
			$data['captcha'] = '';
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} 
			
			$data['download'] = $this->model_catalog_product->getProductDownload($product_id);

			if($data['download']){
				$data['download'] = $this->url->link('product/product/download', 'document_id='.$data['download']);
			}

			$data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			$products = array();
			foreach ($results as $result) {
				$products[] = $result['product_id'];
				//$data['products'][] = $this->load->controller('component/product_info', $result['product_id']);
			}

			$data_mimick_module = array(
				'module_id'		=>	'related_products',
				'title'	=>	array(
					$this->config->get('config_language_id')	=>	$this->language->get('text_related'),
				),
				'product'		=>	$products
			);

			$data['related_products_slider'] = $this->load->controller('extension/module/featured', $data_mimick_module);

			$data['tags'] = array();

			if ($product_info['tag']) {
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			// check live price update for option module is enable or not
			$data['update_price_status'] = $this->config->get('update_price_status');

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$data = $this->load->controller('common/common', $data); // Load header, footer, column left, right, top, bottom

			//Form
            // Populate values after customer logged in
            if($this->customer->isLogged()) {
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
                
                foreach ($this->posts as $post_var => $post_default_value){
                    if( !in_array($post_var, $this->disallow_in_message_body) ){
                        $message .= $this->language->get('entry_' .$post_var) . ": ";
                        //$message .= $this->request->post[$post_var]??"";
                        $message .= $this->request->post[$post_var] ? $this->request->post[$post_var] : "";
                        $message .= "<br/>";
                    }
                }
                
                $mail->setText($message);
                // $mail->send();

                // Pro email Template Mod
                if($this->config->get('pro_email_template_status')){

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
							'enquiry_product' => html_entity_decode($this->request->post['featuredProduct'], ENT_QUOTES, 'UTF-8'),
                            'enquiry_message' => html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')
                            // AJ Apr 15, remarked: 'enquiry_message' => html_entity_decode($message, ENT_QUOTES, 'UTF-8'),
                        ),
                    );
                    
                    $this->model_tool_pro_email->generate($email_params);
                }
                else{
                    $mail->send();
                }
                
                $this->response->redirect($this->url->link('product/product/success'));
			}
			
			// AJ Apr 21: added to carry the validate result to form.
			if ($this->request->server['REQUEST_METHOD'] == 'POST') { 
				$data['validation_failed'] = true;  // POST but validation failed. Need to show the modal window
			} else {
				$data['validation_failed'] = false; 
			}

            $data['action'] = $this->url->link('product/product','product_id=' . (int)$this->request->get['product_id'], true);

			// Captcha
			$data['captcha'] = '';
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
			}

            $data = $this->load->controller('component/common', $data);

            foreach ($this->posts as $post_var => $post_default_value){
                $data[$post_var] = $post_default_value;
                $data['error_' . $post_var] = '';

                // Label Value
                $data['entry_' . $post_var] = $this->language->get('entry_' . $post_var);

                // Post Value
                if( isset($this->request->post[$post_var]) ) {
                    $data[$post_var] = $this->request->post[$post_var];
                }

                // Error Value
                if( isset($this->error[$post_var]) ) {
                    $data['error_' . $post_var] = $this->error[$post_var];
                }
            }
            //debug($this->error);
            //End of Form

			$this->response->setOutput($this->load->view('product/product/product', $data));

		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data = $this->load->controller('common/common', $data); // Load header, footer, column left, right, top, bottom

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}

	}




	/// Likely not in use

	public function review() {
		// if ($this->config->get('config_review_guest') && !$this->customer->isLogged() || !$this->config->get('config_review_guest')) {
		// 	return;
		// }

		if(!$this->config->get('config_review_status')){
			return;
		}

		$this->load->language('product/product');

		// << Related Options / Связанные опции  
		$this->load->language('module/related_options');
		$data['text_ro_clear_options'] 			= $this->language->get('text_ro_clear_options');
		// >> Related Options / Связанные опции

		$this->load->model('catalog/review');

		$data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'email'      => $result['email'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 3;
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('product/review', $data));
	}

	public function write() {
		// if ($this->config->get('config_review_guest') && !$this->customer->isLogged() || !$this->config->get('config_review_guest')) {
		// 	return;
		// }

		if(!$this->config->get('config_review_status')){
			return;
		}

		$this->load->language('product/product');

		// << Related Options / Связанные опции  
		$this->load->language('module/related_options');
		$data['text_ro_clear_options'] 			= $this->language->get('text_ro_clear_options');
		// >> Related Options / Связанные опции

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$this->error['email'] = $this->language->get('error_email');
			}

			if ((utf8_strlen($this->request->post['text']) < 10) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Extension: live price update 
	public function updatePrice() {
		$json = array();
		
		$this->load->model('catalog/product');
		
		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		
		$option_price = 0;
		
		if(isset($this->request->post['option']) && $this->request->post['option']) {
			foreach($this->request->post['option'] as $product_option_id => $value) {
			
				$result = $this->model_catalog_product->getUpdateOptionsList($this->request->get['product_id'], $product_option_id);
				
				if($result) {				
					if($result['type'] == 'select' || $result['type'] == 'radio') {
						$option_values = $this->model_catalog_product->getUpdateOptionValues($value, $product_option_id);
						
						if($option_values) {
							if ($option_values['price_prefix'] == '+') {
								$option_price += $option_values['price'];
							} elseif ($option_values['price_prefix'] == '-') {
								$option_price -= $option_values['price'];
							}
						}
						
					} elseif ($result['type'] == 'checkbox' && is_array($value)) {
						foreach ($value as $product_option_value_id) {
							$option_values = $this->model_catalog_product->getUpdateOptionChcekboxValues($product_option_value_id, $product_option_id);
							
							if($option_values) {
								if ($option_values['price_prefix'] == '+') {
									$option_price += $option_values['price'];
								} elseif ($option_values['price_prefix'] == '-') {
									$option_price -= $option_values['price'];
								}
							}
						}
					}
				} 
			}
		}
		
		$price = $product_info['price'];
		
		$new_price_found = 0;
		// For Discount Amount

		if (isset($this->request->post['quantity'])) {
			$discount_amount = $this->model_catalog_product->getDiscountAmountForUpdatePrice($this->request->get['product_id'], $this->request->post['quantity']);
			
			if ($discount_amount) {
				$price = $discount_amount;
			}
		}
		
		// check for the special price of product
		if ($product_info['special']) {
			$price = $product_info['special'];
			$new_price_found = 1;
		}
		
		$total_price = $price + $option_price;

		 if ( !isset($this->request->post['quantity']) ) {
		 	$this->request->post['quantity'] = 1;
		 }
		
		// Total Calculation
		$unit_price = $this->tax->calculate($total_price, $product_info['tax_class_id'], $this->config->get('config_tax'));
        if ($this->config->get('config_product_decimal_places')) {
			$total = $this->currency->format($unit_price * $this->request->post['quantity'], $this->session->data['currency']);
			$unit_price = $this->currency->format($unit_price, $this->session->data['currency']); // AJ Apr 8: format the unit price
        } else {
			$total = $this->currency->format2($unit_price * $this->request->post['quantity'], $this->session->data['currency']);
			$unit_price = $this->currency->format2($unit_price, $this->session->data['currency']); // AJ Apr 8: foramt the unit price
        }
		
		// Tax Calculation
		$unit_tax = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
		
        if ($this->config->get('config_product_decimal_places')) {
			$tax_total = $this->currency->format(((float)$product_info['special'] ? ($product_info['special'] + $option_price) : ($product_info['price'] + $option_price)) * $this->request->post['quantity'], $this->session->data['currency']);
        } else {
			$tax_total = $this->currency->format2(((float)$product_info['special'] ? ($product_info['special'] + $option_price) : ($product_info['price'] + $option_price)) * $this->request->post['quantity'], $this->session->data['currency']);
        }

		$json['total_price'] = $total;
		$json['new_price_found'] = $new_price_found;
		$json['tax_price'] = $tax_total;
		$json['unit_price'] = $unit_price; // AJ added Apr 7: return unit price to product info page
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Non maintained code
	public function getRecurringDescription() {
		return; // Void

		$this->load->language('product/product');

		// << Related Options / Связанные опции  
		$this->load->language('module/related_options');
		$data['text_ro_clear_options'] 			= $this->language->get('text_ro_clear_options');
		// >> Related Options / Связанные опции
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
			        if ($this->config->get('config_product_decimal_places')) {
						$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			        } else {
						$price = $this->currency->format2($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			        }
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

		        if ($this->config->get('config_product_decimal_places')) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
		        } else {
					$price = $this->currency->format2($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
		        }

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	public function download(){

		$this->load->model('catalog/product');

		$product_pdf_id = 0;

		if( isset($this->request->get['document_id']) && (int)$this->request->get['document_id'] ){
			$product_pdf_id = (int)$this->request->get['document_id'];
		}

		$download_info = $this->model_catalog_product->getDownload($product_pdf_id);

		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					if (ob_get_level()) {
						ob_end_clean();
					}

					readfile($file, 'rb');

					exit();
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->response->redirect($this->url->link('product/product', '', true));
		}
	}
	// << OPTIONS IMAGE
	public function getoptionimages() {
		$json = array();

		$product_option_image_mode = $this->config->get('theme_default_product_option_image_mode');

		if($product_option_image_mode == 1) {
			// for combination option
			
			//debug($this->request->get['option']);
			if( isset($this->request->get['prodid']) && isset($this->request->get['option']) && !empty($this->request->get['option'])) {
				$this->load->model('tool/image');
				$theme = $this->config->get('config_theme');
				$image_popup_width = $this->config->get( $theme . '_image_popup_width');
				$image_popup_height = $this->config->get( $theme . '_image_popup_height');
				$image_thumb_width = $this->config->get( $theme . '_image_thumb_width');
				$image_thumb_height = $this->config->get( $theme . '_image_thumb_height');
				$image_additional_width = $this->config->get( $theme . '_image_additional_width');
				$image_additional_height = $this->config->get( $theme . '_image_additional_height');
				$this->load->model('catalog/product');
				
				$results = array();
				$count_imgs = array();
				$count_total_imgs = array();
				
				foreach($this->request->get['option'] as $poid => $pov_id) {
					$po_imgs = $this->model_catalog_product->getProductOptionImagesByOption($this->request->get['prodid'], $poid, $pov_id);

					if(!empty($po_imgs)) {
						foreach($po_imgs as $poimg) {
							// get all the product option images (with same product image id) - every time check for option combination images
							$all_prod_opt_imgs = $this->model_catalog_product->getAllProductOptionImagesByPIID($this->request->get['prodid'], $poimg['product_image_id']);

							if(!empty($all_prod_opt_imgs)) {
								$count_total_imgs[$poimg['product_image_id']] = count($all_prod_opt_imgs);

								// part for doing count of the selected option combination and its image
								if(in_array($poimg['product_option_value_id'], array_column($all_prod_opt_imgs, 'product_option_value_id'))) {
									// if not yet initialized then count start as 1
									if(!isset($count_imgs[$poimg['product_image_id']])) {
										$count_imgs[$poimg['product_image_id']]['count'] = 1;
										$count_imgs[$poimg['product_image_id']]['image'] = $poimg['image'];
									}
									// continue count + 1
									else {
										$count_imgs[$poimg['product_image_id']]['count']++;
										$count_imgs[$poimg['product_image_id']]['image'] = $poimg['image'];
									}
								}
							}
						}
					}				
				}

				if(!empty($count_imgs) && !empty($count_total_imgs)) {
					foreach($count_imgs as $cik => $civ) {
						// to check if selected option combination match with the exact option combination images (by comparing both count)
						if($count_imgs[$cik]['count'] == $count_total_imgs[$cik]) {
							$results[$cik] = array(
								'image' => $civ['image'],
							);
						}
					}
				}
				
				$json['images'] = array();
				foreach ($results as $result) {
					$json['images'][] = array(
						'popup' => $this->model_tool_image->resize($result['image'], $image_popup_width, $image_popup_height),
						'thumb' => $this->model_tool_image->resize($result['image'], $image_thumb_width, $image_thumb_height)
					);
				}
				$json['additional_images'] = array();
				foreach ($results as $result) {
					$json['additional_images'][] = array(
						'popup' => $this->model_tool_image->resize($result['image'], $image_popup_width, $image_popup_height),
						'thumb' => $this->model_tool_image->resize($result['image'], $image_additional_width, $image_additional_height)
					);
				}
			}	
		}
		else {
			// for normal option
			if( isset($this->request->get['prodid']) && isset($this->request->get['poid']) && isset($this->request->get['povid']) ) {
				$this->load->model('tool/image');
				$theme = $this->config->get('config_theme');
				$image_popup_width = $this->config->get( $theme . '_image_popup_width');
				$image_popup_height = $this->config->get( $theme . '_image_popup_height');
				$image_thumb_width = $this->config->get( $theme . '_image_thumb_width');
				$image_thumb_height = $this->config->get( $theme . '_image_thumb_height');
				$image_additional_width = $this->config->get( $theme . '_image_additional_width');
				$image_additional_height = $this->config->get( $theme . '_image_additional_height');
				$this->load->model('catalog/product');
				$results = $this->model_catalog_product->getProductOptionImagesByOption($this->request->get['prodid'], $this->request->get['poid'], $this->request->get['povid']);
				
				$json['images'] = array();
				foreach ($results as $result) {
					$json['images'][] = array(
						'popup' => $this->model_tool_image->resize($result['image'], $image_popup_width, $image_popup_height),
						'thumb' => $this->model_tool_image->resize($result['image'], $image_thumb_width, $image_thumb_height)
					);
				}
				$json['additional_images'] = array();
				foreach ($results as $result) {
					$json['additional_images'][] = array(
						'popup' => $this->model_tool_image->resize($result['image'], $image_popup_width, $image_popup_height),
						'thumb' => $this->model_tool_image->resize($result['image'], $image_additional_width, $image_additional_height)
					);
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function ajaxgetprodimages() {
		$data = array();
		if(isset($this->request->get['product_id']) && isset($this->request->get['prodimage'])) {
			$data = $this->getprodimages($this->request->get['product_id'], $this->request->get['prodimage']);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function getprodimages($product_id, $product_image, $data = array()) {
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$theme = $this->config->get('config_theme');
		$image_popup_width = $this->config->get( $theme . '_image_popup_width');
		$image_popup_height = $this->config->get( $theme . '_image_popup_height');
		$image_thumb_width = $this->config->get( $theme . '_image_thumb_width');
		$image_thumb_height = $this->config->get( $theme . '_image_thumb_height');
		$data['image_thumb_width'] = 'max-width:'.$image_thumb_width.'px;';
		$image_additional_width = $this->config->get( $theme . '_image_additional_width');
		$image_additional_height = $this->config->get( $theme . '_image_additional_height');
		
		$results = $this->model_catalog_product->getProductImages($product_id);
		$data['images'] = array();
		if( is_file(DIR_IMAGE . $product_image)) {
			$data['images'][] = array(
				'popup' => $this->model_tool_image->resize($product_image, $image_popup_width, $image_popup_height),
				'thumb' => $this->model_tool_image->resize($product_image, $image_thumb_width, $image_thumb_height)
			);
		}
		else{
			$data['images'][] = array(
				'popup' => $this->model_tool_image->resize('no_image.png', $image_popup_width, $image_popup_height),
				'thumb' => $this->model_tool_image->resize('no_image.png', $image_thumb_width, $image_thumb_height)
			);
		}
		
		foreach ($results as $result) {
			$data['images'][] = array(
				'popup' => $this->model_tool_image->resize($result['image'], $image_popup_width, $image_popup_height),
				'thumb' => $this->model_tool_image->resize($result['image'], $image_thumb_width, $image_thumb_height)
			);
		}
		
		$data['additional_images'] = array();
		if( is_file(DIR_IMAGE . $product_image)) {
			$data['additional_images'][] = array(
				'popup' => $this->model_tool_image->resize($product_image, $image_popup_width, $image_popup_height),
				'thumb' => $this->model_tool_image->resize($product_image, $image_additional_width, $image_additional_height)
			);
		}
		else{
			$data['additional_images'][] = array(
				'popup' => $this->model_tool_image->resize('no_image.png', $image_popup_width, $image_popup_height),
				'thumb' => $this->model_tool_image->resize('no_image.png', $image_additional_width, $image_additional_height)
			);
		}
		foreach ($results as $result) {
			$data['additional_images'][] = array(
				'popup' => $this->model_tool_image->resize($result['image'], $image_popup_width, $image_popup_height),
				'thumb' => $this->model_tool_image->resize($result['image'], $image_additional_width, $image_additional_height)
			);
		}
		return $data;
	}
	
	public function ajaxgetcolorimages() {
		$data = array();
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$theme = $this->config->get('config_theme');
		$width = $this->config->get($theme . '_image_product_width');
		$height = $this->config->get($theme . '_image_product_height');
		if(isset($this->request->get['product_id']) && isset($this->request->get['product_option_id']) && isset($this->request->get['product_option_value_id'])) {
			$color_image = $this->model_catalog_product->getProductColorImage($this->request->get['product_id'],$this->request->get['product_option_id'],$this->request->get['product_option_value_id']);
			$data['image'] = $this->model_tool_image->resize('no_image.png', $width, $height);
			if(!empty($color_image)){
				$color_image['image'];
				
				if( is_file(DIR_IMAGE . $color_image['image'])) {
					$data['image'] = $this->model_tool_image->resize($color_image['image'], $width, $height);
				}
			} else {
                        //get default image
				$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
				if (is_file(DIR_IMAGE . $product_info['image']) && $product_info['image']){
					$data['image'] = $this->model_tool_image->resize($product_info['image'], $width, $height);
				}
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
    // >> OPTIONS IMAGE

	protected function validate() {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
                $this->error['name'] = $this->language->get('error_name');
            }

			// AJ Apr 21: no need to validate, it's hidden field
            // if ((utf8_strlen($this->request->post['subject']) < 3) || (utf8_strlen($this->request->post['subject']) > 32)) {
            //     $this->error['subject'] = $this->language->get('error_subject');
            // }
            
            if ((int)$this->request->post['telephone'] < 1) {
                $this->error['telephone'] = $this->language->get('error_telephone');
            }
            
            if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                $this->error['email'] = $this->language->get('error_email');
            }
            
            if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
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

    public function success() {

            $facebook_pixel_event_params_FAE = array(
                'event_name' => 'Lead');
              // stores the pixel params in the session
              $this->request->post['facebook_pixel_event_params_FAE'] =
                addslashes(json_encode($facebook_pixel_event_params_FAE));
                
            $this->load->language('product/category');
            
            $this->document->setTitle($this->language->get('heading_title'));
            
            $data['breadcrumbs'] = array();
            
            $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
            );

            $data['heading_title'] = $this->language->get('heading_title');
            
            $data['text_message'] = $this->language->get('text_success');
            
            $data['button_continue'] = $this->language->get('button_continue');
            
            $data['continue'] = $this->url->link('common/home');
            
            $data = $this->load->controller('component/common', $data); 

            
            $data['pixel_tracking'] = "
            <script>
            fbq('track', 'Contact');
            </script>
            ";
            
            $this->response->setOutput($this->load->view('common/success', $data));
    }
}
