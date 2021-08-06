<?php
class ControllerExtensionModuleModuleHelper extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules

        $choices = array(
            array(
                'label' => 'Yes',
                'value' => 1,
            ),
            array(
                'label' => 'No',
                'value' => 0,
            ),
        );

		$array = array(
            'oc' => $this,
            'heading_title' => 'Sample Module Helper',
            'modulename' => 'module_helper',
            'auto_increment' => true, // for auto increment number
            'fields' => array(
                array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                array('type' => 'textarea', 'label' => 'Main Description 1', 'name' => 'main_description1','ckeditor'=>true),
                array('type' => 'textarea', 'label' => 'Main Description 2', 'name' => 'main_description2','ckeditor'=>true),
                array('type' => 'upload', 'label' => 'Upload', 'name' => 'upload'),
                array('type' => 'repeater', 'label' => 'Items', 'name' => 'items',
                    'fields' => array(
                        array('type' => 'text', 'label' => 'ID', 'name' => 'id', 'readonly' => true), // for auto increment number
                        array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                        array('type' => 'text', 'label' => 'Promotion Text', 'name' => 'promotion_text'),
                        array('type' => 'text', 'label' => 'Text', 'name' => 'text'),
                        array('type' => 'text', 'label' => 'Button Label', 'name' => 'label'),
                        array('type' => 'text', 'label' => 'Button Redirect Link', 'name' => 'link'),
                        array('type' => 'image', 'label' => 'Banner Image', 'name' => 'image'),
                        array('type' => 'textarea', 'label' => 'Description', 'name' => 'description','ckeditor'=>true),
                        array('type' => 'upload', 'label' => 'Upload', 'name' => 'upload'),
                        array('type' => 'date', 'label' => 'Date', 'name' => 'date'),
                        array('type' => 'dropdown', 'label' => 'Dropdown', 'name' => 'dropdown', 'choices' => $choices),
                    )
                ),
            ),
        );

        // Without Repeater
        // $array = array(
        //     'oc' => $this,
        //     'heading_title' => 'Module Helper',
        //     'modulename' => 'module_helper',
        //     'fields' => array(
        //         array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
        //         array('type' => 'text', 'label' => 'Text', 'name' => 'text'),
        //         array('type' => 'text', 'label' => 'Button Label', 'name' => 'label'),
        //         array('type' => 'text', 'label' => 'Button Redirect Link', 'name' => 'link'),
        //         array('type' => 'image', 'label' => 'Background Image', 'name' => 'background_image'),
        //     ),
        // );

        $this->modulehelper->init($array);    
	}
}
