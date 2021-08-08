<?php
class ControllerModuleQuickModal extends Controller
{
	
	public function index() {

		$this->document->addStyle('catalog/view/javascript/quickmodal/quickmodal.css');
		$this->document->addScript('catalog/view/javascript/quickmodal/pushon.js');
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

	}
	
    public function quickmodaldata()
    {
        $this->load->model('catalog/product');
        
        $this->load->model('tool/image');
        
        $this->load->language('product/product');
        
		
        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }
        $product_info = $this->model_catalog_product->getProduct($product_id);
        
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model']        = $this->language->get('text_model');
        $data['text_reward']       = $this->language->get('text_reward');
        $data['text_points']       = $this->language->get('text_points');
        $data['text_stock']        = $this->language->get('text_stock');
        $data['text_discount']     = $this->language->get('text_discount');
        $data['text_tax']          = $this->language->get('text_tax');
        
        $data['entry_qty']         = $this->language->get('entry_qty');
        $data['entry_name']        = $this->language->get('entry_name');
        
        $data['button_cart']       = $this->language->get('button_cart');
        $data['button_wishlist']   = $this->language->get('button_wishlist');
        $data['button_compare']    = $this->language->get('button_compare');
        $data['button_upload']     = $this->language->get('button_upload');
        $data['button_continue']   = $this->language->get('button_continue');
        
        
        $data['heading_title'] = $product_info['name'];
        
        if ($product_info['product_id']) {
            $data['product_id'] = $product_info['product_id'];
        } else {
            $data['product_id'] = '';
        }
        
        if ($product_info['name']) {
            $data['name'] = $product_info['name'];
        } else {
            $data['name'] = '';
        }
        
        if ($product_info['image']) {
            $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
        } else {
            $data['popup'] = '';
        }
        
        if ($product_info['image']) {
            $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
        } else {
            $data['thumb'] = '';
        }
        
        $data['images'] = array();
        
        $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
        
        foreach ($results as $result) {
            $data['images'][] = array(
                'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'))
            );
        }
        
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
        } else {
            $data['price'] = false;
        }
        
        if ((float) $product_info['special']) {
            $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
        } else {
            $data['special'] = false;
        }
        
        if ($this->config->get('config_tax')) {
            $data['tax'] = $this->currency->format((float) $product_info['special'] ? $product_info['special'] : $product_info['price']);
        } else {
            $data['tax'] = false;
        }
        
        $data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
        
        $this->response->setOutput($this->load->view('default/template/module/quickmodal.tpl', $data));
        
    }
	
	public function productid_by_keyword(){
	
		$json = array();
		
		if (isset($this->request->post['keyword'])) {
			$keyword = $this->request->post['keyword'];
		} else {
			$keyword = '';
		}
		if ($keyword) {	
			$querys = $this->db->query("SELECT `query`  FROM `" . DB_PREFIX . "url_alias` WHERE `keyword` = '" . $this->db->escape($keyword) . "'");
			foreach ($querys->rows as $query) {
				if(preg_match("/product_id/i", $query['query'])){
					$json['success'] = $query['query'];
					break;
				}
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	
}