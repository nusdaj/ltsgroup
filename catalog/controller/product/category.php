<?php
class ControllerProductCategory extends Controller {
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

		$data = array();

		// Load Languages
		$data = array_merge($data, $this->load->language('product/category'));
		// << Related Options / Связанные опции  
		$this->load->language('module/related_options');
		$data['text_ro_clear_options'] 			= $this->language->get('text_ro_clear_options');
		// >> Related Options / Связанные опции

		$this->document->setTitle($data['heading_title']);

		$data['text_compare'] = sprintf($data['text_compare'], (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$theme = $this->config->get('config_theme');

		$sort_default = 'p.sort_order';

		if($this->config->get('product_category_sort_order_status') && isset($this->request->get['path'])){
			$sort_default = 'p2co.sort_order, p.sort_order, LCASE(pd.name)';  // AJ Apr 9: add LcASE to wrap pd.name. for consistency with others
		}

		/* AJ Apr 9: begin: add a new default random sorting. BTW, the above two statements are useless now. */
		$sort_default = 'p.random';
		/* AJ Apr 9: end */

		$listing_conditions = array(  // AJ Apr 9 remarks: this is default settings, when no query string passed in
			// Listing
			'sort'				=>	$sort_default,
			'order'				=>	'ASC',
			'page'				=>	1,
			'limit'				=>	(int)$this->config->get( $theme . '_product_limit'),
			// Filtering
			'path'				=>	0,
			'price_min' 		=>	0,
			'price_max' 		=>	0,
			// filter length
			'length_min' 		=>	0,
			'length_max' 		=>	0,
			// filter length END
			'manufacturer_id'	=>	'',
			'filter'			=>	'',
		);

		// Filter Url to apply for Pagination / Breadcrumbs
		$url_filter = array(
			'pagination_filter'		=>	'page,path',
			'breadcrumbs_filter'	=>	'page,path',
		);

		foreach ($listing_conditions as $var => &$default){
			if(isset($this->request->get[$var])){
				$default	=	$this->request->get[$var];
			}

			if($var=='sort'){
				$sort_n_order = explode('-', $default);
				
				$order = $listing_conditions[$var];
				if(count($sort_n_order) > 1){
					$order	=	$sort_n_order[1];
				}
				
				${$var}	=	$sort_n_order[0];
			}
			elseif($var != 'order'){
				${$var}	=	$default;
			}
			
		}

		foreach($url_filter as $url => $skip){
			${$url}	= '';
			foreach ($listing_conditions as $var => $default_l){
				if( !strpos( '_' . $skip, $var) && $default_l){ 
					${$url} .= '&' . $var . '=' . ${$var};
				}
			}
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['heading_title'],
			//'href' => $this->url->link('product/category') . $breadcrumbs_filter
			'href' => $this->url->link('product/category')
		);

		// Load Category Portion

		$category_info = array();

		$categories = array();

		if($path){
			$this->load->model('catalog/category');

			$categories = explode('_', $path);

			$category_path = array();
			foreach($categories as $category_id){
				$category_info = $this->model_catalog_category->getCategory($category_id);

				if($category_info){
					$category_path[] = $category_id;

					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . implode('_', $category_path) . $breadcrumbs_filter)
					);
				}

				if($category_info){
					
					$data['heading_title'] = $category_info['name'];

					$this->document->setTitle($category_info['meta_title']);

					$this->document->setDescription($category_info['meta_description']);

					$this->document->setKeywords($category_info['meta_keyword']);

				}
			}
			
		}
		// End Load Category

		$data['compare'] = $this->url->link('product/compare');

		$data['products'] = array();

		$filter_data = array(
			'filter_category_id' => $categories?end($categories):0,
			'filter_manufacturer'=> $manufacturer_id,
			'filter_sub_category'=> true,
			'filter_special'	 => false,
			'price_min'			 => $price_min,
			'price_max'			 => $price_max,
			// filter length
			'length_min'		 => $length_min,
			'length_max'		 => $length_max,
			// filter length END
			'filter_filter'      => $filter,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit,				
		); // debug($filter_data);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
			
		$results = $this->model_catalog_product->getProducts($filter_data);

		if($category_info){
			$this->facebookcommonutils = new FacebookCommonUtils();
			$params = new DAPixelConfigParams(array(
			'eventName' => 'ViewCategory',
			'products' => $results,
			'currency' => $this->currency,
			'currencyCode' => $this->session->data['currency'],
			'hasQuantity' => false,
			'isCustomEvent' => false,
			'paramNameUsedInProductListing' => 'content_category',
			'paramValueUsedInProductListing' => $category_info['name']));
			$facebook_pixel_event_params_FAE =
			$this->facebookcommonutils->getDAPixelParamsForProductListing($params);
			// stores the pixel params in the session
			$this->request->post['facebook_pixel_event_params_FAE'] =
			addslashes(json_encode($facebook_pixel_event_params_FAE));
		}
		else{

			// Default category
			$this->facebookcommonutils = new FacebookCommonUtils();
			$params = new DAPixelConfigParams(array(
			'eventName' => 'ViewCategory',
			'products' => $results,
			'currency' => $this->currency,
			'currencyCode' => $this->session->data['currency'],
			'hasQuantity' => false,
			'isCustomEvent' => false,
			'paramNameUsedInProductListing' => 'content_category',
			'paramValueUsedInProductListing' => $this->language->get('text_products')));
			$facebook_pixel_event_params_FAE =
			$this->facebookcommonutils->getDAPixelParamsForProductListing($params);
			// stores the pixel params in the session
			$this->request->post['facebook_pixel_event_params_FAE'] =
			addslashes(json_encode($facebook_pixel_event_params_FAE));
			
		}

		$after_clean = '';
		if( isset($this->request->get['path']) && !is_array($this->request->get['path']) ){
			$after_clean = '&';
			$before_clean = $this->request->get['path'];
			$before_clean = explode('_', $before_clean);
			foreach($before_clean as $key => $category_id){
				if((int)$category_id < 1){
					unset($before_clean[$key]);
				}
			}

			if($before_clean){
				$after_clean = 'path=' .implode('_', $before_clean);
			}
		}

		foreach ($results as $result) {
			$data['products'][] = $this->load->controller('component/product_info', array( 'product_id' => $result['product_id'], 'url' => $after_clean));
		}

		// Sort
			
		$type_of_sort = array(
			'name'	=>	'pd.name',
			'price'	=>	'p.price',
			'price'	=>	'p.price',
			'model'	=>	'p.model',
		);

		if ($this->config->get('config_review_status')) {
			$type_of_sort['rating'] = 'rating';
		}

		$data['sorts'] = array();

		// Default
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default_asc'),
			'value' => 'p.random-ASC',  // AJ Apr 9, rmarked again for new default random ordering // 'p.sort_order-ASC',  // AJ modified Apr 9: add "p.", before "sort_order-ASC"
		);

		// The rest of the ordering from $type_of_sort
		foreach($type_of_sort as $type => $column){
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_' . $type . '_asc'),
				'value' => $column.'-ASC',
			);
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_' . $type . '_desc'),
				'value' => $column.'-DESC',
			);
		}
		// End Sort

		// Limit
		$data['limits'] = array();
		
		$config_limit = $this->config->get($theme . '_product_limit');

		// AJ Mar 24: begin; make the limits reasonable.
		// $limits = range($config_limit, $config_limit*5, $config_limit);
		$limits = range($config_limit/2, $config_limit*1.5, $config_limit/2);
		// AJ Mar 24: end;
		
		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
			);
		}
		
		// End Limit

		$path = '';
		if(isset($this->request->get['path'])){
			$path = 'path=' . $this->request->get['path'] . '&';
		}

		$page_data = array(
			'total'	=>	$product_total,
			'page'	=>	$page,
			'limit'	=>	$limit,
			'url'	=>	$this->url->link('product/category', $path . 'page={page}'. $pagination_filter),
		); 

		//debug($page_data);
		$data = array_merge($this->load->controller('component/pagination', $page_data), $data);
		
		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
		    $this->document->addLink($this->url->link('product/category', '', true), 'canonical');
		} elseif ($page == 2) {
		    $this->document->addLink($this->url->link('product/category', '', true), 'prev');
		} else {
		    $this->document->addLink($this->url->link('product/category', 'page='. ($page - 1), true), 'prev');
		}

		if ($limit && ceil($product_total / $limit) > $page) {
		    $this->document->addLink($this->url->link('product/category', 'page='. ($page + 1), true), 'next');
		}

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

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
                        $message .= $this->language->get('entry_' .$post_var) . ":\n";
                        //$message .= $this->request->post[$post_var]??"";
                        $message .= $this->request->post[$post_var] ? $this->request->post[$post_var] : "";
                        $message .= "\n\n";
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
                            // 'enquiry_message' => html_entity_decode($message, ENT_QUOTES, 'UTF-8'),
                        ),
                    );
                    
                    $this->model_tool_pro_email->generate($email_params);
                }
                else{
                    $mail->send();
                }
                
                $this->response->redirect($this->url->link('product/category/success'));
			}
			
			// AJ Apr 21: added to carry the validate result to form.
			if ($this->request->server['REQUEST_METHOD'] == 'POST') { 
				$data['validation_failed'] = true;  // POST but validation failed. Need to show the modal window
			} else {
				$data['validation_failed'] = false; 
			}

            $data['action'] = $this->url->link('product/category', '', true);

			// // Captcha
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
            $this->response->setOutput($this->load->view('product/category', $data));
            //End of Form
	}

	     protected function validate() {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
                $this->error['name'] = $this->language->get('error_name');
            }

			// AJ Apr 21: no need to verify, because it's hidden input
            // if ((utf8_strlen($this->request->post['subject']) < 3) || (utf8_strlen($this->request->post['subject']) > 32)) {
            //     $this->error['subject'] = $this->language->get('error_subject');
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

// Original Line: 422
// After reduced: 268