<?php
	/* AJ Apr 17, comment: the table seo_url is NEVER used. Instead, the priate array $static serves the purpose as table seo_url
	   AJ Apr 22: Studied the whole chunk of code, SEO_URL is never used. And the static is actually the seo_url. OC v2.3 is pretty strange. SEO_URL is one table
				  and it has an admin module, but it is never used. However, give up! It is not important at all. Don't want waste time.
	 */
	class ControllerStartupSeoUrl extends Controller {

		/* AJ Apr 17, comment: this is strange; all should store in db, why here? We will store the array in table url_alias
		   a copy is still leave here as $static. If any problems, change the table url_alias first. 
		 */
		private $static = array(  
			// SEO Keyword => Route
			'faqs'					=>	'information/faq',
			'contact-us'			=>	'information/contact',
			'login'					=>	'account/login',
			'register'				=>	'account/register',
			'forgotten'				=>	'account/forgotten',
			'search'				=>	'product/search',
			'special'				=>	'product/special',
			'products'				=>	'product/category',
			'manufacturer'			=>	'product/manufacturer',	
			'cart'					=>	'checkout/cart',
			'checkout'				=>	'quickcheckout/checkout',
			'checkout-success' 		=>	'checkout/success',
			'print'	 		   		=>	'account/print',
			'contact-success'		=>	'information/contact/success',
			'account'				=>	'account/account',
			'edit'					=>	'account/edit',
			'address'				=>	'account/address',
			'address-update'		=>	'account/address/edit',
			'address-new'			=>	'account/address/add',
			'account-password'		=>	'account/password',
			'order-history'			=>	'account/order',
			'order-info'			=>	'account/order/info',
			'testimonial'			=>	'testimonial/testimonial',
			'gift-cards' 			=> 	'product/gift_card_category',
		);
		
		public function index() {

			// Add rewrite to url class
			if ($this->config->get('config_seo_url')) {
				$this->url->addRewrite($this);
			}
			
			// Decode URL
			if (isset($this->request->get['_route_'])) {
				$parts = explode('/', $this->request->get['_route_']);
				
				// remove any empty arrays from trailing
				if (utf8_strlen(end($parts)) == 0) {
					array_pop($parts);
				}
				
				foreach ($parts as $part) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
					
					if ($query->num_rows) {
						$url = explode('=', $query->row['query']);
						
						if ($url[0] == 'product_id') {
							$this->request->get['product_id'] = $url[1];
						}
						
						if ($url[0] == 'category_id') {
							if (!isset($this->request->get['path'])) {
								$this->request->get['path'] = $url[1];
							}
							else {
								$this->request->get['path'] .= '_' . $url[1];
							}
						}

						/* completecombo */
						if ($url[0] == 'salescombopge_id') {
				            $this->request->get['salescombopge_id'] = $url[1];
				            $this->request->get['page_id'] = $url[1];
			          	}
						/* completecombo */
						
						if ($url[0] == 'manufacturer_id') {
							$this->request->get['manufacturer_id'] = $url[1];
						}
						
						if ($url[0] == 'information_id') {
							$this->request->get['information_id'] = $url[1];
						}
						
						if ($query->row['query'] && $url[0] != 'information_id' && $url[0] != 'manufacturer_id' && $url[0] != 'category_id' && $url[0] != 'product_id') {
							// $this->request->get['route'] = $query->row['query'];
							/* completecombo */
							if($url[0] != 'salescombopge_id') {
					          	$this->request->get['route'] = $query->row['query'];
					        }
							/* completecombo */
						}

						// for gift card inner SEO
						if ($url[0] == 'voucher_theme_id') {
							$this->request->get['voucher_theme_id'] = $url[1];
							$this->request->get['route'] = 'product/gift_card';
						}
						// for gift card inner SEO
					}
					elseif( isset($this->static[$part]) ){
						$this->request->get['route'] = $this->static[$part];
						//break;
					}
					/* elseif( $part == 'products' ){ // AJ Apr 17: comment: not proper to hard-code speically this route only
						$this->request->get['route'] = 'product/category';
						//break;
					} */ // AJ Apr 22: this branch is useless, because it's alredy defined in the $static
					else {
						$this->request->get['route'] = 'error/not_found';						
						// break;
					}
				}
				
				if (!isset($this->request->get['route'])) {
					if (isset($this->request->get['product_id'])) {
						$this->request->get['route'] = 'product/product';
					}
					elseif (isset($this->request->get['path'])) {
						$this->request->get['route'] = 'product/category';
					} 
					/* completecombo */
					elseif (isset($this->request->get['salescombopge_id'])) {
      					$this->request->get['route'] = 'offers/salescombopge'; 
      				}
					/* completecombo */
					elseif (isset($this->request->get['manufacturer_id'])) {
						$this->request->get['route'] = 'product/manufacturer/info';
					}
					elseif (isset($this->request->get['information_id'])) {
						$this->request->get['route'] = 'information/information';
					}
				}
				
				if ($this->request->get['route'] != 'product/product' && $this->request->get['route'] != 'product/category' && $this->request->get['route'] != 'product/manufacturer/info' && $this->request->get['route'] != 'information/information') {
					$blog_headlines = $this->config->get('ncategory_bnews_headlines_url') ? $this->config->get('ncategory_bnews_headlines_url') : 'blog-headlines';
					
					$blogparts = explode('/', $this->request->get['_route_']);
					
					if (utf8_strlen(end($blogparts)) == 0) {
						array_pop($blogparts);
					}
					
					
					foreach ($blogparts as $part) {
						/* default article seo urls */
						if (strpos($part, 'blogcat') === 0) {
							$ncatid = (int)str_replace("blogcat", "", $part);
							if (!isset($this->request->get['ncat'])) {
								$this->request->get['ncat'] = $ncatid;
							}
							else {
								$this->request->get['ncat'] .= '_' . $ncatid;
							}
						}
						if (strpos($part, 'blogart') === 0) {
							$this->request->get['news_id'] = (int)str_replace("blogart", "", $part);
						}
						if (strpos($part, 'blogauthor') === 0) {
							$this->request->get['author'] = (int)str_replace("blogauthor", "", $part);
						}
						if (strpos($part, 'blogarchive-') === 0) {
							$this->request->get['archive'] = (string)str_replace("blogarchive-", "", $part);
						}
						/* end of default article urls */
						$query     =     $this->db->query( " SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "' " );
						
						if ($part == $blog_headlines) {
							$query->num_rows = true;
							$query->row['query'] = "-=-";
						}
						
						if ($query->num_rows) {
							$url = explode('=', $query->row['query']);
							/* custom article urls */
							if ($url[0] == 'news_id') {
								$this->request->get['news_id'] = $url[1];
							}
							if ($url[0] == 'nauthor_id') {
								$this->request->get['author'] = $url[1];
							}
							if ($url[0] == 'ncategory_id') {
								if (!isset($this->request->get['ncat'])) {
									$this->request->get['ncat'] = $url[1];
								}
								else {
									$this->request->get['ncat'] .= '_' . $url[1];
								}
							}
							/* end of custom article urls */
						}
					}
					if (!isset($this->request->get['route']) || (isset($this->request->get['route']) && $this->request->get['route'] == "error/not_found")) {
						
						if (isset($this->request->get['news_id'])) {
							$this->request->get['route'] = 'news/article';
						}
						elseif (isset($this->request->get['ncat']) || isset($this->request->get['author']) || $this->request->get['_route_'] ==  $blog_headlines || isset($this->request->get['archive'])) {
							$this->request->get['route'] = 'news/ncategory';
						}
					}
				}
				
			}
		}
		
		public function rewrite($link) {
			$url_info = parse_url(str_replace('&amp;', '&', $link));
			
			$url = '';
			
			$data = array();
			
			parse_str($url_info['query'], $data); // debug($data);

			if ( in_array($data['route'], $this->static) ){
				$url .= '/' . array_search($data['route'], $this->static);
			}
			elseif ($data['route'] == 'common/home'){
				$url = '/';
			}
			
			foreach ($data as $key => $value) {
				if (isset($data['route'])) {
					
					// to remove 'products' keyword for product sub categories
					if ($data['route'] == 'product/category' && isset($data['path'])) {
						$url = '';
					}
					// to remove 'products' keyword for product sub categories

					if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
						
						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
							
							unset($data[$key]);
						}
					}
					// for gift card inner SEO
					elseif (($data['route'] == 'product/gift_card' && $key == 'voucher_theme_id')) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
						
						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
							
							unset($data[$key]);
						}
					}
					// for gift card inner SEO
					elseif ($data['route'] == 'news/article' && $key == 'news_id') { 
						$query    =    $this->db->query( "SELECT * FROM " . DB_PREFIX . "url_alias where  `query` = '" . $this->db->escape($key . '=' . (int)$value ) . "'");
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
							unset($data[$key]);
						}
						else {
							$url .= '/blogart' . (int)$value;	
							unset($data[$key]);
						}
					}
					elseif (($data['route'] == 'news/ncategory' || $data['route'] == 'news/article') && $key == 'author') { 
						$realkey = "nauthor_id";
						$query   =    $this->db->query( "SELECT * FROM " . DB_PREFIX . "url_alias where  `query` = '" . $this->db->escape($realkey . '=' . (int)$value) . "'" );
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
							unset($data[$key]);
						}
						else {
							$url .= '/blogauthor' . (int)$value;	
							unset($data[$key]);
						}
					}
					elseif (($data['route'] == 'news/ncategory' || $data['route'] == 'news/article') && $key == 'archive') { 
						$url .= '/blogarchive-' . (string)$value;	
						unset($data[$key]);
					}
					elseif ($key == 'ncat') {
						$ncategories = explode('_', $value);
						
						foreach ($ncategories as $ncategory) {
							$query    =    $this->db->query( "SELECT * FROM " . DB_PREFIX . "url_alias where  `query` = 'ncategory_id=" . (int)$ncategory . "'" );
							if ($query->num_rows) {
								$url .= '/' . $query->row['keyword'];
							}
							else {
								$url .= '/blogcat' . $ncategory;
							}
						}
						unset($data[$key]);
					}
					elseif ((isset($data['route']) && $data['route'] == 'news/ncategory' && $key != 'ncat' && $key != 'author' && $key != 'page' && $key != 'archive') || (isset($data['route']) && $data['route'] == 'news/article' && $key != 'page')) { 
						$blog_headlines = $this->config->get('ncategory_bnews_headlines_url') ? $this->config->get('ncategory_bnews_headlines_url') : 'blog-headlines';
						$url .=  '/'.$blog_headlines;

					/* completecombo */
					} else if ($data['route'] == 'offers/salescombopge' && $key == 'page_id') {
				          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'salescombopge_id=" . (int)$value . "'");

				          if ($query->num_rows && $query->row['keyword']) {
				            $url .= '/' . $query->row['keyword'];

				            unset($data[$key]);
				          }
					}
					/* completecombo */
					elseif ($key == 'path') {
						$categories = explode('_', $value);
						
						foreach ($categories as $category) {
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
							
							if ($query->num_rows && $query->row['keyword']) {
								$url .= '/' . $query->row['keyword'];
							}
							else {
								$url = '';
								
								break;
							}
						}
						
						unset($data[$key]);
					}
					
				}
			}
			
			if ($url) {
				unset($data['route']);
				
				$query = '';
				
				if ($data) {
					foreach ($data as $key => $value) {
						$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
					}
					
					if ($query) {
						$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
					}
				}
				
				return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
			}
			else {
				return $link;
			}
		}
	}
