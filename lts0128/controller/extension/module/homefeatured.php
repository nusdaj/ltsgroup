<?php
class ControllerExtensionModuleHomefeatured extends Controller {
	public function index() {
        $choices = array(
            array(
                'label' => 'Green',
                'value' => 'bg-green.jpg',
            ),
            array(
                'label' => 'Grey',
                'value' => 'bg-grey.jpg',
            ),
            array(
                'label' => 'Orange',
                'value' => 'bg-orange.jpg',
            ),
            array(
                'label' => 'Yellow',
                'value' => 'bg-yellow.jpg',
            ),
        );

		$array = array(
            'oc' => $this,
            'heading_title' => 'Home > Featured',
            'modulename' => 'homefeatured',
            'fields' => array(
                array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                array('type' => 'repeater', 'label' => 'Featured Categories', 'name' => 'category',
                    'fields' => array(
                        array('type' => 'dropdown', 'label' => 'Background', 'name' => 'background', 'choices' => $choices),
                        array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
                        array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                        array('type' => 'text', 'label' => 'Button Label', 'name' => 'label'),
                        array('type' => 'text', 'label' => 'Button Link', 'name' => 'link'),
                    )
                ),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
