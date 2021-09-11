<?php
	/*
	 * AJ Sep 6: Add a module to allow administrators choose top categories and 
	 *           generate catalogue and pricelist from them.
	 */
	class ControllerCatalogPricelist extends Controller {
		// private $error = array();
		
		public function index() {
			$this->load->language('catalog/pricelist');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/category');
			
			$this->getList();
		}

		public function generate() {
			$type = $this->request->post['booktype'];
			if (strcasecmp($type,'catalogue') == 0) {
				$this->catalogue();
			}

			if (strcasecmp($type,'pricelist') == 0) {
				$this->pricelist();
			}
		}

		// export catalogue
		private function catalogue() {
			$this->load->language('catalog/pricelist');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('catalog/attribute');
			$this->load->model('tool/image');

			$filter_data = array(
				'filter_name' => "Description" // This attribute name is hardcoded. 
			);
			$attribute_ids = $this->model_catalog_attribute->getAttributes($filter_data); // by right, there should return only 1 record
			$attribute_id = $attribute_ids[0]['attribute_id'];

			if (isset($this->request->post['selected'])) {
				$data['categories'] = array();

				foreach ($this->request->post['selected'] as $category_id) {
					$category = $this->model_catalog_category->getCategory($category_id);

					$products = array();
					$filter_data = array(
						'parent_id'  => $category_id
					);
					$subcats = $this->model_catalog_category->getCategories($filter_data);
					foreach ($subcats as $subcat) {
						$filter_data = array(
							'filter_category_id' => $subcat['category_id']
						);
						$results = $this->model_catalog_product->getProducts($filter_data);
						foreach ($results as $result) {
							$product_id = $result['product_id'];
							// $product_info = $this->model_catalog_product->getProdut($product_id);

							$attributes = $this->model_catalog_product->getProductAttributes($product_id);
							foreach ($attributes as $attribute) {
								if ($attribute['attribute_id'] == $attribute_id) {
									$full_description = $attribute['product_attribute_description'];
									break;
								}
							}

							$products[] = array(
								'product_id' 	=> $product_id,
								'subcat_name' 	=> $subcat['name'],
								'name' 			=> $result['name'],
								'model' 		=> $result['model'],
								'image' 		=> $this->model_tool_image->resize($result['image'], 150, 150),
								'short_desc' 	=> $result['description'],  // this is short description. full description is in "Description“ attribute. Our design.
								'description' 	=> $full_description
							);
						}
					}
					$data['categories'][] = array(
						'category_id' 	=> $category_id,
						'name' 			=> $category['name'],
						'image' 		=> $this->model_tool_image->resize($category['image'], 200, 200),
						'description' 	=> $category['description'],
						'products' 		=> $products
					);
				}
				
				$this->load->view('catalog/pricelist_catalogue', $data); // pass data to view and generate the HTML format catalogue.
			}
			
			$this->getList();
		}

		// export pricelist
		private function pricelist() {

		}
		
		protected function getList() {
			
			$data['token'] = $this->session->data['token'];
			
			$data['export_url'] = html($this->url->link('catalog/category/export', 'token=' . $this->session->data['token'], true));
			
			$sort = 'name';
			$order = 'ASC';
			// $page = 1;
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			}
			
			// if (isset($this->request->get['page'])) {
			// 	$page = $this->request->get['page'];
			// }
			
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			// if (isset($this->request->get['page'])) {
			// 	$url .= '&page=' . $this->request->get['page'];
			// }
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/pricelist', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			// $data['catalogue'] = $this->url->link('catalog/pricelist/catalogue', 'token=' . $this->session->data['token'] . $url, true);
			// $data['pricelist'] = $this->url->link('catalog/pricelist/pricelist', 'token=' . $this->session->data['token'] . $url, true);
			$data['generate'] = $this->url->link('catalog/pricelist/generate', 'token=' . $this->session->data['token'] . $url, true);

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {
				$filter_status = null;
			}

			$data['categories'] = array();
			
			$filter_data = array(
				'filter_name'	  => $filter_name,
				'filter_status'   => $filter_status,
				'sort'  => $sort,
				'order' => $order,
				'parent_id' => 0  // AJ Sep 6: add to search only top-level categories
			);
			
			// $category_total = $this->model_catalog_category->getTotalCategories($filter_data);
			
			$results = $this->model_catalog_category->getCategories($filter_data);
			
			$label = $this->language->get('text_backend_only'); // This has span encapsulating the text

			foreach ($results as $result) {
				$data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'] . ($result['backend_only']?$label:''),
				'short_name'  => $result['short_name'],
				'sort_order'  => $result['sort_order'],
				'status'      => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'shref' 		  => str_replace(HTTPS_SERVER, HTTPS_CATALOG, $this->url->link('product/special&path=' . $result['category_path'])),
				'href' 		  => str_replace(HTTPS_SERVER, HTTPS_CATALOG, $this->url->link('product/category&path=' . $result['category_path'])),
				// 'edit'        => $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, true),
				// 'delete'      => $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, true)
				);
			}
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_status'] = $this->language->get('entry_status');

			$data['text_list'] = $this->language->get('text_list');
			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_no_results'] = $this->language->get('text_no_results');
			$data['text_confirm'] = $this->language->get('text_confirm');
			
			$data['column_name'] = $this->language->get('column_name');
			$data['column_sort_order'] = $this->language->get('column_sort_order');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_URL'] = $this->language->get('column_URL');
			
			// $data['button_add'] = $this->language->get('button_add');
			// $data['button_edit'] = $this->language->get('button_edit');
			$data['button_catalogue'] = $this->language->get('button_catalogue');
			$data['button_pricelist'] = $this->language->get('button_pricelist');
			$data['button_clear'] = $this->language->get('button_clear');
			$data['button_filter'] = $this->language->get('button_filter');
			
			// $data['error_warning'] = '';
			$data['success'] = '';
			$data['selected'] = array();

			$data['filter_name'] = $filter_name;
			$data['filter_status'] = $filter_status;
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			}
			
			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
			}
			
			if (isset($this->request->post['selected'])) {
				$data['selected'] = (array)$this->request->post['selected'];
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if ($order == 'ASC') {
				$url .= '&order=DESC';
			}
			else {
				$url .= '&order=ASC';
			}
			
			// if (isset($this->request->get['page'])) {
			// 	$url .= '&page=' . $this->request->get['page'];
			// }
			
			$data['sort_name'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
			$data['sort_sort_order'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);
			$data['sort_status'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=c2.status' . $url, true);
			
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			// $pagination = new Pagination();
			// $pagination->total = $category_total;
			// $pagination->page = $page;
			// $pagination->limit = $this->config->get('config_limit_admin');
			// $pagination->url = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
			
			// $data['pagination'] = $pagination->render();
			
			// $data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('catalog/pricelist', $data));
		}
	}
