<?php

/*
|--------------------------------------------------------------------------
|   Dais
|--------------------------------------------------------------------------
|
|   This file is part of the Dais Framework package.
|	
|	(c) Vince Kronlein <vince@dais.io>
|	
|	For the full copyright and license information, please view the LICENSE
|	file that was distributed with this source code.
|	
*/

namespace Admin\Controller\Feed;
use Dais\Engine\Controller;

class GoogleBase extends Controller {
    private $error = array();
    
    public function index() {
        $data = Theme::language('feed/google_base');
        
        Theme::setTitle($this->language->get('lang_heading_title'));
        
        Theme::model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('google_base', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('lang_text_success');
            
            Response::redirect($this->url->link('module/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        $this->breadcrumb->add('lang_text_feed', 'module/feed');
        $this->breadcrumb->add('lang_heading_title', 'feed/google_base');
        
        $data['action'] = $this->url->link('feed/google_base', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['cancel'] = $this->url->link('module/feed', 'token=' . $this->session->data['token'], 'SSL');
        
        if (isset($this->request->post['google_base_status'])) {
            $data['google_base_status'] = $this->request->post['google_base_status'];
        } else {
            $data['google_base_status'] = Config::get('google_base_status');
        }
        
        $data['data_feed'] = Config::get('http.public') . 'feed/google_base';
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::render_controllers($data);
        
        Response::setOutput(Theme::view('feed/google_base', $data));
    }
    
    protected function validate() {
        if (!User::hasPermission('modify', 'feed/google_base')) {
            $this->error['warning'] = $this->language->get('lang_error_permission');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
