<?php
    class ControllerComponentProductInfo extends Controller{
        public function index($product_id = 0){

            $url = '';

            if( is_array($product_id) ){
                $url = $product_id['url']; 
                $product_id = $product_id['product_id']; 
            }

            $this->load->language('component/product_info');
            // Clean Value
            $product_id = (int)$product_id;

            // No product id pass into this controller
            if(!$product_id) return '';

            $this->load->model('tool/image');
            $this->load->model('catalog/product');
            $this->load->model('catalog/category');

            $product_info = $this->model_catalog_product->getProduct($product_id);

            // Product Disabled / Deleted
            if(!$product_info) return '';

            $theme = $this->config->get('config_theme');
            $width = $this->config->get($theme . '_image_product_width');
            $height = $this->config->get($theme . '_image_product_height');

            $image = $this->model_tool_image->resize('no_image.png', $width, $height);
            $image2 = false;
            
            $price = false;
            $special = false;
            $rating = false;
            $tax = false;

            $category = "";

            $category = $this->model_catalog_product->getProductAllCategories($product_id);
            if($category) {
                $category = end($category)['path_id'];
                $category = $this->model_catalog_category->getCategory($category);
                if($category) {
                    $category = $category['name'];
                }
            }

            if (is_file(DIR_IMAGE . $product_info['image']) && $product_info['image']) 
                $image = $this->model_tool_image->resize($product_info['image'], $width, $height);

            if (is_file(DIR_IMAGE . $product_info['image2']) && $product_info['image2']) 
                $image2 = $this->model_tool_image->resize($product_info['image2'], $width, $height);
            
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')){
                if ($this->config->get('config_product_decimal_places')) {
                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = $this->currency->format2($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                }
            }

            /* AJ Apr 11, begin: format discount price  */
            if ((float)$product_info['discount']) {
                if ($this->config->get('config_product_decimal_places')) {
                    $discount = $this->currency->format($product_info['discount'], $this->session->data['currency']);
                } else {
                    $discount = $this->currency->format2($product_info['discount'], $this->session->data['currency']);
                }
            }
            /* AJ Apr 11, end  */
            
            if ((float)$product_info['special']) {
                if ($this->config->get('config_product_decimal_places')) {
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $special = $this->currency->format2($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                }
            }
            
            if ($this->config->get('config_tax')) 
                $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
            
            if ($this->config->get('config_review_status'))
                $rating = (int)$product_info['rating'];

            // $options = $this->model_catalog_product->getProductOptions($product_id);

            $product_url = $this->url->link('product/product', 'product_id=' . $product_info['product_id']);

            if ($url) {
                $product_url = $this->url->link('product/product', $url . '&product_id=' . $product_info['product_id']);
            }

            $sticker = $this->load->controller('component/sticker', $product_info['product_id']);
            if ( $product_info['quantity']<=0 ) {

                $sticker = array(
                    'name' => $this->language->get('text_out_of_stock'),
                    'color' => '#ffffff',
                    'background-color' => '#cdcad2',
                    'image' => '',
                );

            }
            /* options */

            $options = array();

            if ($this->config->get('config_display_option_product_list')) {
                foreach ($this->model_catalog_product->getProductOptions($product_info['product_id']) as $option) {
                    $product_option_value_data = array();

                    foreach ($option['product_option_value'] as $option_value) {
                        if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                            if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                                if ($this->config->get('config_product_decimal_places')) {
                                    $option_price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
                                } else {
                                    $option_price = $this->currency->format2($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
                                }
                            } else {
                                $option_price = false;
                            }

                            $product_option_value_data[] = array(
                                'product_option_value_id' => $option_value['product_option_value_id'],
                                'option_value_id'         => $option_value['option_value_id'],
                                'name'                    => $option_value['name'],
                                'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
                                'price'                   => $option_price,
                                'price_prefix'            => $option_value['price_prefix']
                            );
                        }
                    }

                    $options[] = array(
                        'product_option_id'    => $option['product_option_id'],
                        'product_option_value' => $product_option_value_data,
                        'option_id'            => $option['option_id'],
                        'name'                 => $option['name'],
                        'type'                 => $option['type'],
                        'value'                => $option['value'],
                        'required'             => $option['required']
                    );
                }
            }

            $not_avail = false;
            if($product_info['quantity'] <= 0) {
                $not_avail = true;
            }

            $hover_image_change = $this->config->get('config_hover_image_change');

            $info = array(
                'show_special_sticker' => $special ? 1 : 0,
                'product_id'        => $product_info['product_id'],
                'category'          => $category,
                'thumb'             => $image, 
				'thumb2'            => $image2, 
                'hover_image_change'=> $hover_image_change, 
                'name'              => $product_info['name'],  
                'minimum'           => $product_info['minimum'],
				'description'       => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($theme . '_product_description_length')),
                'price'             => $price,
                'discount'          => $discount, // AJ Apr 11, added
				'special'           => $special,
				'tax'               => $tax,
				'rating'            => $rating,
                'href'              => $product_url,
                'enquiry'           => ((float)$product_info['price'] <= 0),
                'sticker'           =>  $sticker,
                'out_of_stock'      =>  $this->config->get('config_stock_checkout')?'':($product_info['quantity']>0?'':'out-of-stock'),
                'not_avail'         => $not_avail,
                'options'           => $options, 
                'text_tax'          => $this->language->get('text_tax'),
                'text_sale'         => $this->language->get('text_sale'),
                'text_option'       => $this->language->get('text_option'),
                'text_select'       => $this->language->get('text_select'),
                'text_cart'         => $this->language->get('text_cart'),
                'text_loading'      => $this->language->get('text_loading'),
                'entry_qty'         => $this->language->get('text_quantity'),
                'button_cart'       => $this->language->get('button_cart'),
                'button_wishlist'   => $this->language->get('button_wishlist'),
                'button_compare'    => $this->language->get('button_compare'),
                'button_enquiry'    => $this->language->get('button_enquiry'),
                'label_enquiry'     => $this->language->get('label_enquiry'),
                'more_options'      => count($options) > 1?$this->language->get('text_more_options_available'):'',
            );

            return $this->load->view('component/product_info', $info);
        }
    }