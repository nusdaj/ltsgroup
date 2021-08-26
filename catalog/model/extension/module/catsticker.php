<?php
/* AJ Aug 26: The same-named backend-module will be kept for reference only. And this front-end module will be kept
   as is. It is never complete, nor functional!!!
   We will take a totally different way to handle the stickers. In short, we will create a category branch (contians two levels),
   which is exactly the same as the other branches. And we use the same code to display the branch. 
   What we need to do is to enhance the current sticker module (if any) with a function to update the category branch lively.
   */
    class ModelExtensionModuleCatsticker extends Model{
        public function getStickers() {
            $stickers = $this->config->get('sticker'); 
            // debug($stickers);

            if($stickers){
                foreach($stickers as $sticker){
                    $this->load->model('tool/image');
                    $thumb = $this->model_tool_image->resize('placeholder.png', 50, 50);
                    if(is_file(DIR_IMAGE . $sticker['image'])){
                        $thumb = $this->model_tool_image->resize($sticker['image'], 50, 50);
                    }
    
                    // $products = array();
                    // if ($sticker['products']) {
                    //     foreach($data['product_list'] as $product){
                    //         if( in_array($product['product_id'], $sticker['products']) ){
                    //             $products[] = $product;
                    //         }
                    //     }
                    // }
                    
                    $data['stickers'][]	=	array(
                        'name'				=>	$sticker['name'],
                        'percentage'		=>	$sticker['percentage'],
                        'label_color'		=>	$sticker['label_color'],
                        'sticker_color'		=>	$sticker['sticker_color'],
                        'thumb'				=>	$thumb,
                        'image'				=>	$sticker['image'],
                        'duration'			=>	$sticker['duration'],
                        'products'			=>	$sticker['products'] // $products
                    );
                }
            }
        }
    }