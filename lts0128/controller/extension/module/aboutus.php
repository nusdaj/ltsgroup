<?php
class ControllerExtensionModuleAboutus extends Controller {
	public function index() {

		$array = array(
            'oc' => $this,
            'heading_title' => 'About Us',
            'modulename' => 'aboutus',
            'fields' => array(
                array('type' => 'image', 'label' => 'Icon 1', 'name' => 'icon1'),
                array('type' => 'text', 'label' => 'Icon Title 1', 'name' => 'ititle1'),
                array('type' => 'text', 'label' => 'Icon Link 1', 'name' => 'iicon1'),
                array('type' => 'image', 'label' => 'Icon 2', 'name' => 'icon2'),
                array('type' => 'text', 'label' => 'Icon Title 2', 'name' => 'ititle2'),
                array('type' => 'text', 'label' => 'Icon Link 2', 'name' => 'iicon2'),
                array('type' => 'image', 'label' => 'Icon 3', 'name' => 'icon3'),
                array('type' => 'text', 'label' => 'Icon Title 3', 'name' => 'ititle3'),
                array('type' => 'text', 'label' => 'Icon Link 3', 'name' => 'iicon3'),
                array('type' => 'image', 'label' => 'Icon 4', 'name' => 'icon4'),
                array('type' => 'text', 'label' => 'Icon Title 4', 'name' => 'ititle4'),
                array('type' => 'text', 'label' => 'Icon Link 4', 'name' => 'iicon4'),
                array('type' => 'image', 'label' => 'Icon 5', 'name' => 'icon5'),
                array('type' => 'text', 'label' => 'Icon Title 5', 'name' => 'ititle5'),
                array('type' => 'text', 'label' => 'Icon Link 5', 'name' => 'iicon5'),
                array('type' => 'text', 'label' => 'Title 1', 'name' => 'title1'),
                array('type' => 'textarea', 'label' => 'Description 1', 'name' => 'description1','ckeditor'=>true),
                array('type' => 'text', 'label' => 'Title 2', 'name' => 'title2'),
                array('type' => 'textarea', 'label' => 'Description 2', 'name' => 'description2','ckeditor'=>true),
                array('type' => 'text', 'label' => 'Title 3', 'name' => 'title3'),
                array('type' => 'textarea', 'label' => 'Description 3', 'name' => 'description3','ckeditor'=>true),
                array('type' => 'repeater', 'label' => 'Services', 'name' => 'services',
                    'fields' => array(
                        array('type' => 'image', 'label' => 'Icon', 'name' => 'icon'),
                        array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                        array('type' => 'textarea', 'label' => 'Description', 'name' => 'description','ckeditor'=>true),
                    )
                ),
                 array('type' => 'repeater', 'label' => 'Clients', 'name' => 'client',
                    'fields' => array(
                        array('type' => 'image', 'label' => 'Icon', 'name' => 'icon'),
                    )
                ),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
