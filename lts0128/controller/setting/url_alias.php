<?php
    class ControllerSettingUrlAlias extends Controller {
        
        private $error = array();
        
        public function index() {
            $this->load->language('setting/url_alias');
            
            $this->document->setTitle($this->language->get('heading_title'));
            
            $this->load->model('setting/url_alias');

            // AJ Apr 22, begin: the original implementation is rubish. You just cannot delete all records and store them back
            // because there are limitation in the number of records fetched from the database. 
            // if ($this->request->server['REQUEST_METHOD'] == 'POST'){ 
                
            //     $url_alias = $this->request->post['url_alias'];
            //     if(!empty($url_alias)){
            //         $this->db->query("DELETE FROM ".DB_PREFIX."url_alias");
            //         $values = array();
            //         foreach($url_alias as $urla){
            //             if($urla['route'] !=""){
            //                 $values[] =  "('" . $urla['route'] . "', '" . $urla['keyword'] . "')";
            //             }
            //         }
            //         $sql = "INSERT INTO ".DB_PREFIX."url_alias (query, keyword) VALUES " . implode(', ',$values). "";
            //         $this->db->query($sql);
            //         $this->session->data['success'] = $this->language->get('text_success');
            //         $this->response->redirect($this->url->link('setting/url_alias', 'token=' . $this->session->data['token'], true));
                    
            //     }
            // }
            if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                $i = 0;
                $url_alias_id = "";
                $query = "";
                $keyword = "";

                foreach ($_POST as $key => $value) {  // process RAW data, because each input has different name. the only known is it's grouped in 4
                    $mod = $i % 4;
                    $i++;
                    switch ($mod) {
                        case 0: $url_alias_id = $value; break;
                        case 1: $query = $value; break;
                        case 2: $keyword = $value; break;
                        case 3: 
                            switch ($value) {  // action
                                case "update": 
                                    $this->model_setting_url_alias->editAlias($url_alias_id, $query, $keyword); 
                                    break;
                                case "delete": 
                                    if ($url_alias_id > 0) $this->model_setting_url_alias->deleteAlias($url_alias_id);
                                    break;
                                case "add": 
                                    $this->model_setting_url_alias->addAlias($query, $keyword);
                                    break;
                                default: break; // nothing to do
                            }
                            break;

                        default: break; // WTF. what's wrong?                             
                    }
                }
            }
            // AJ Apr 22, end: process the POST'ed form and update table
            
            $urlaliases = $this->model_setting_url_alias->getUrlaliases();
            $data['urlaliases'] = $urlaliases;
            
            $data['heading_title'] = $this->language->get('heading_title');
            
            $data['text_list'] = $this->language->get('text_list');
            $data['text_edit'] = $this->language->get('text_edit');
            
            
            $data['column_route'] = $this->language->get('column_route');
            $data['column_keyword'] = $this->language->get('column_keyword');
            
            
            $data['column_url_alias_id'] = $this->language->get('column_url_alias_id'); // AJ Apr 21: added for the column of url_alias_id
            $data['button_add'] = $this->language->get('button_add');
            $data['button_cancel'] = $this->language->get('button_cancel');
            
            
            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
                } else {
                $data['error_warning'] = '';
            }

            $data['breadcrumbs'] = array();
            
            $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
            );
            
            $data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/url_alias', 'token=' . $this->session->data['token'], true)
            );
            
            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];
                
                unset($this->session->data['success']);
                } else {
                $data['success'] = '';
            }
            
            $data['action'] = $this->url->link('setting/url_alias', 'token=' . $this->session->data['token'], true);
            $data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);
            
            $data['token'] = $this->session->data['token'];
            
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            
            $this->response->setOutput($this->load->view('setting/url_alias', $data));
        }
        
        
    }                                