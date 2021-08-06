<?php
class ControllerProductSearch extends Controller {
	/* AJ Apr 15, begin: copied from home.php controller 
	   AJ Apr 14, begin: form POST handler data */
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
	/* AJ Apr 14, end: form POST handler data 
	   AJ Apr 15, end: copied from home.php controller */

	public function index() {
		$this->load->language('product/search');
		
		// << Related Options / Связанные опции  
		$this->load->language('module/related_options');
		$data['text_ro_clear_options'] 			= $this->language->get('text_ro_clear_options');
		// >> Related Options / Связанные опции

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$theme = $this->config->get('config_theme');

		/* AJ Apr 15, begin: Process POST data, copied from home.php controller */
		/* AJ Apr 14, begin: handler to POST form data */
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
			
			foreach ($this->posts as $post_var => $post_default_value) {
				if( !in_array($post_var, $this->disallow_in_message_body) ){
					$message .= $this->language->get('entry_' .$post_var) . ": ";
					$message .= $this->request->post[$post_var] ? $this->request->post[$post_var] : $post_default_value;
					$message .= "<br />";
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
						'enquiry_product' => html_entity_decode($this->request->post['featuredProduct'], ENT_QUOTES, 'UTF-8'),  // AJ Apr 14: add product name in the email
						'enquiry_message' => html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')
						// 'enquiry_message' => html_entity_decode($message, ENT_QUOTES, 'UTF-8')
					),
				);
				
				$this->model_tool_pro_email->generate($email_params);
			}
			else{
				$mail->send();
			}
			
			$this->response->redirect($this->url->link('product/category/success'));
		}
		/* AJ Apr 14, end: handler to POST form data */

		// AJ Apr 21: added to carry the validate result to form.
		if ($this->request->server['REQUEST_METHOD'] == 'POST') { 
			$data['validation_failed'] = true;  // POST but validation failed. Need to show the modal window
		} else {
			$data['validation_failed'] = false; 
		}

		// AJ Apr 21, added: Captcha
		$data['captcha'] = '';
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
		}		

		// AJ Apr 12: load in language file
		$data = array_merge($data, $this->load->language('common/home'));

		// AJ Apr 14: set POST action target
		$data['action'] = $this->url->link('common/home', '', true);
		/* AJ Apr 15, end: Process POST data */

		$sort_default = 'p.sort_order';
		
		if($this->config->get('product_category_sort_order_status') && isset($this->request->get['category_id']) && (int)$this->request->get['category_id']){
			$sort_default = 'p2co.sort_order, p.sort_order, LCASE(pd.name)';
		}

		$listing_conditions = array(
			// Input
			'search'			=>	'',
			'tag'				=>	'',
			'description'		=>	1,
			'sub_category'		=>	1,
			'category_id'		=>	0,
			// Listing
			'sort'				=>	$sort_default,
			'order'				=>	'ASC',
			'page'				=>	1,
			'limit'				=>	(int)$this->config->get( $theme . '_product_search_limit'),
			// Filtering
			'path'				=>	0,
			'price_min' 		=>	0,
			'price_max' 		=>	0,
			'manufacturer_id'	=>	'',
			'filter'			=>	'',
		);

		// Filter Url to apply for Pagination / Breadcrumbs
		$url_filter = array(
			'pagination_filter'		=>	'page,order,tag,description,sub_category,sort,limit',
			'breadcrumbs_filter'	=>	'page,order,tag,description,sub_category,sort,limit',
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
		} //debug($listing_conditions);

		foreach($url_filter as $url => $skip){
			${$url}	= '';
			foreach ($listing_conditions as $var => $default){
				if( !strpos( '_' . $skip, $var) && $default){ 
					${$url} .= '&' . $var . '=' . ${$var};
				}
			}
		}

		if (isset($this->request->get['search'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
		} elseif (isset($this->request->get['tag'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/search', $url)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_description'] = sprintf($this->language->get('text_description'), '', '');
		if (isset($this->request->get['search'])) {
			$data['text_description'] = sprintf($this->language->get('text_description'), $this->request->get['search'], '');
		}

		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_search'] = $this->language->get('text_search');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_sub_category'] = $this->language->get('text_sub_category');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');

		$data['entry_search'] = $this->language->get('entry_search');
		$data['entry_description'] = $this->language->get('entry_description');

		$data['button_search'] = $this->language->get('button_search');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');

		$data['compare'] = $this->url->link('product/compare');

		$this->load->model('catalog/category');

		// 3 Level Category Search
		$data['categories'] = array();

		$categories_1 = $this->model_catalog_category->getCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = array();

			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = array();

				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}

				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);
			}

			$data['categories'][] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}

		$data['products'] = array();

		//if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$filter_data = array(
				'filter_name'         => $search,
				'filter_tag'          => $tag,
				'filter_description'  => $description,
				'filter_category_id'  => $category_id,
				'filter_sub_category' => $sub_category,
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);

			$product_total = $this->model_catalog_product->getTotalProductsSoundex($filter_data);

			$results = $this->model_catalog_product->getProductsSoundex($filter_data);

			$this->facebookcommonutils = new FacebookCommonUtils();
			$params = new DAPixelConfigParams(array(
				'eventName' => 'Search',
				'products' => $results,
				'currency' => $this->currency,
				'currencyCode' => $this->session->data['currency'],
				'hasQuantity' => false,
				'isCustomEvent' => false,
				'paramNameUsedInProductListing' => 'search_string',
				'paramValueUsedInProductListing' => $search));
			$facebook_pixel_event_params_FAE =
				$this->facebookcommonutils->getDAPixelParamsForProductListing($params);
			// stores the pixel params in the session
			$this->request->post['facebook_pixel_event_params_FAE'] =
				addslashes(json_encode($facebook_pixel_event_params_FAE));

			foreach ($results as $result) {
				$data['products'][] = $this->load->controller('component/product_info', $result['product_id']);
			}

			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/search', 'sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/search', 'sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/search', 'sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/search', 'sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/search', 'sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/search', 'sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/search', 'sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/search', 'sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/search', 'sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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

			$data['limits'] = array();

			$config_limit = $this->config->get($this->config->get('config_theme') . '_product_search_limit');

			$limits = range($config_limit, $config_limit*5, $config_limit);

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/search', $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$page_data = array(
				'total' =>	$product_total,
				'page'	=>	$page,
				'limit'	=>	$limit,
				'url'	=>	$this->url->link('product/search', 'page={page}' . $pagination_filter),
			);

			$data = array_merge($this->load->controller('component/pagination', $page_data), $data);
			
			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/search', '', true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('product/search', '', true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('product/search', $url . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/search', $url . '&page='. ($page + 1), true), 'next');
			}

			if (isset($this->request->get['search']) && $this->config->get('config_customer_search')) {
				$this->load->model('account/search');

				if ($this->customer->isLogged()) {
					$customer_id = $this->customer->getId();
				} else {
					$customer_id = 0;
				}

				if (isset($this->request->server['REMOTE_ADDR'])) {
					$ip = $this->request->server['REMOTE_ADDR'];
				} else {
					$ip = '';
				}

				$search_data = array(
					'keyword'       => $search,
					'category_id'   => $category_id,
					'sub_category'  => $sub_category,
					'description'   => $description,
					'products'      => $product_total,
					'customer_id'   => $customer_id,
					'ip'            => $ip
				);

				$this->model_account_search->addSearch($search_data);
			}
		// }

		$data['search'] = $search;
		$data['description'] = $description;
		$data['category_id'] = $category_id;
		$data['sub_category'] = $sub_category;

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data = $this->load->controller('component/common', $data);

		$this->response->setOutput($this->load->view('product/search', $data));
	}
}

// Original Line: 471
// After Edit: