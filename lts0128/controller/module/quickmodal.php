<?php
class ControllerModuleQuickModal extends Controller
  {
    private $error = array();
    
    public function index()
      {
        $this->load->language('module/quickmodal');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->document->addStyle('view/javascript/bootstrap/css/bootstrap-multiselect.min.css');
        $this->document->addScript('view/javascript/bootstrap/js/bootstrap-multiselect.min.js');
        
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
          {
            $this->model_setting_setting->editSetting('quickmodal', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->assign_quickmodal($this->request->post['quickmodal_layouts']);
            
            $this->createquickmodal($this->request->post['quickmodal_selector'], $this->request->post['quickmodal_b_style'], $this->request->post['quickmodal_b_text'], $this->request->post['quickmodal_css']);
            
            $this->response->redirect($this->url->link('module/quickmodal', 'token=' . $this->session->data['token'], 'SSL'));
          }
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_edit']     = $this->language->get('text_edit');
        $data['text_enabled']  = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_alert']    = $this->language->get('text_alert');
		$data['help_quickmodal']  = $this->language->get('help_quickmodal');
        
        $data['entry_status']   = $this->language->get('entry_status');
        $data['entry_selector'] = $this->language->get('entry_selector');
        $data['entry_mb']       = $this->language->get('entry_mb');
        $data['entry_b_style']  = $this->language->get('entry_b_style');
        $data['entry_b_text']   = $this->language->get('entry_b_text');
        $data['entry_assign']   = $this->language->get('entry_assign');
        
        $data['button_save']   = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        
        if (isset($this->error['warning']))
          {
            $data['error_warning'] = $this->error['warning'];
          }
        else
          {
            $data['error_warning'] = '';
          }
        
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/quickmodal', 'token=' . $this->session->data['token'], 'SSL')
        );
        
        $data['action'] = $this->url->link('module/quickmodal', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['token'] = $this->session->data['token'];
        
        if (isset($this->request->post['quickmodal_selector']))
          {
            $data['quickmodal_selector'] = $this->request->post['quickmodal_selector'];
          }
        else
          {
            $data['quickmodal_selector'] = $this->config->get('quickmodal_selector');
          }
        
        if (isset($this->request->post['quickmodal_b_style']))
          {
            $data['quickmodal_b_style'] = $this->request->post['quickmodal_b_style'];
          }
        else
          {
            $data['quickmodal_b_style'] = $this->config->get('quickmodal_b_style');
          }
        
        if (isset($this->request->post['quickmodal_b_text']))
          {
            $data['quickmodal_b_text'] = $this->request->post['quickmodal_b_text'];
          }
        else
          {
            $data['quickmodal_b_text'] = $this->config->get('quickmodal_b_text');
          }
        
        if (isset($this->request->post['quickmodal_layouts']))
          {
            $data['quickmodal_layouts'] = $this->request->post['quickmodal_layouts'];
          }
        else
          {
            $data['quickmodal_layouts'] = $this->config->get('quickmodal_layouts');
          }
        
        if (isset($this->request->post['quickmodal_status']))
          {
            $data['quickmodal_status'] = $this->request->post['quickmodal_status'];
          }
        else
          {
            $data['quickmodal_status'] = $this->config->get('quickmodal_status');
          }
        
        if (isset($this->request->post['quickmodal_css']))
          {
            $data['quickmodal_css'] = $this->request->post['quickmodal_css'];
          }
        else
          {
            $data['quickmodal_css'] = file_get_contents(DIR_CATALOG . "view/javascript/quickmodal/quickmodal.css");
          }
        
        $data['token']       = $this->session->data['token'];
        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('module/quickmodal.tpl', $data));
        
      }
    
    public function createquickmodal($a, $b, $c, $d)
      {
        
        $filejs = fopen(DIR_CATALOG . "view/javascript/quickmodal/pushon.js", 'w');
        
        $filecss = fopen(DIR_CATALOG . "view/javascript/quickmodal/quickmodal.css", 'w');
        
        $outputjs = file_get_contents(DIR_CATALOG . "view/javascript/quickmodal/quickmodal.js");
        
        $outputjs .= "$(document).ready(function() {
	$('" . $a . "').each(function(){
	quickview_button(this,'" . $b . "','" . $c . "');});
	init_modal();
	});";
        
        fwrite($filejs, $outputjs);
        fwrite($filecss, $d);
        
      }
    
    public function assign_quickmodal($data = array())
      {
        $this->db->query("DELETE FROM " . DB_PREFIX . "layout_module  WHERE code= 'quickmodal'");
        foreach ($data as $key => $value)
          {
            $this->db->query("INSERT INTO " . DB_PREFIX . "layout_module SET layout_id = '" . (int) $key . "', code = 'quickmodal', position = 'content_top', sort_order = '0'");
          }
      }
    
    public function autocomplete()
      {
        if (isset($this->request->get['filter_name']))
          {
            
            $querys = $this->db->query("SELECT layout_id, name 
				FROM `" . DB_PREFIX . "layout`
				WHERE  `name` LIKE '" . $this->db->escape($this->request->get['filter_name']) . "%'");
            foreach ($querys->rows as $query)
              {
                $data[$query['layout_id']] = array(
                    'layout_id' => $query['layout_id'],
                    'name' => $query['name']
                );
              }
            
          }
        
        $json = empty($data) ? array() : $data;
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
      }
    
    protected function validate()
      {
        if (!$this->user->hasPermission('modify', 'module/quickmodal'))
          {
            $this->error['warning'] = $this->language->get('error_permission');
          }
        
        if (!$this->request->post['quickmodal_selector'])
          {
            $this->error['warning'] = $this->language->get('error_warning');
          }
        
        if (!$this->request->post['quickmodal_b_style'])
          {
            $this->error['warning'] = $this->language->get('error_warning');
          }
        
        if (!$this->request->post['quickmodal_b_text'])
          {
            $this->error['warning'] = $this->language->get('error_warning');
          }
        
        return !$this->error;
      }
  }