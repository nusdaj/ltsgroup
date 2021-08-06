<?php
class ControllerExtensionModuleProductCatalogue extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules

		$array = array(
            'oc' => $this,
            'heading_title' => 'Product Catalogue',
            'modulename' => 'product_catalogue',
            'fields' => array(
                array('type' => 'upload', 'label' => 'Catalogue', 'name' => 'catalogue'),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
