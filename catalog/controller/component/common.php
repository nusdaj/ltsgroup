<?php
class ControllerComponentCommon extends Controller
{
    public function index($data)
    {
        $data['column_left']    = $this->load->controller('common/column_left');
        $data['column_right']   = $this->load->controller('common/column_right');
        $data['content_top']    = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer']         = $this->load->controller('common/footer');
        $data['header']         = $this->load->controller('common/header');

        // AJ Apr 9: Add in enquiry modal model
        $data['enquiry_modal'] = $this->load->controller('common/enquiry_modal');

        return $data;
    }
}
