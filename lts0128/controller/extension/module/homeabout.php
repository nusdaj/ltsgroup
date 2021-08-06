<?php
class ControllerExtensionModuleHomeabout extends Controller {
	public function index() {

		$array = array(
            'oc' => $this,
            'heading_title' => 'Home > Services',
            'modulename' => 'homeabout',
            'fields' => array(
                array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                array('type' => 'text', 'label' => 'Sub Title', 'name' => 'subtitle'),
                array('type' => 'repeater', 'label' => 'Services', 'name' => 'services',
                    'fields' => array(
                        array('type' => 'image', 'label' => 'Icon', 'name' => 'icon'),
                        array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                    )
                ),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
