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

namespace Admin\Controller\Widget;
use Dais\Engine\Controller;

class FooterBlocks extends Controller {
    private $error = array();
    
    public function index() {
        $data = Theme::language('widget/footer_blocks');
        Theme::setTitle($this->language->get('lang_heading_title'));
        Theme::model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('footer_blocks', $this->request->post);
            $this->session->data['success'] = $this->language->get('lang_text_success');
            
            Response::redirect($this->url->link('module/widget', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        $this->breadcrumb->add('lang_text_widget', 'module/widget');
        $this->breadcrumb->add('lang_heading_title', 'widget/footer_blocks');
        
        $data['action'] = $this->url->link('widget/footer_blocks', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('module/widget', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['widgets'] = array();
        
        if (isset($this->request->post['footer_blocks_widget'])) {
            $data['widgets'] = $this->request->post['footer_blocks_widget'];
        } elseif (Config::get('footer_blocks_widget')) {
            $data['widgets'] = Config::get('footer_blocks_widget');
        }
        
        Theme::model('module/menu');
        
        $data['menus'] = array();
        
        $menus = $this->model_module_menu->getMenus();
        
        foreach ($menus as $menu):
            $data['menus'][] = array('menu_id' => $menu['menu_id'], 'name' => $menu['name']);
        endforeach;
        
        Theme::model('design/layout');
        
        $data['layouts'] = $this->model_design_layout->getLayouts();
        
        Theme::loadjs('javascript/widget/footer_blocks', $data);
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::render_controllers($data);
        
        Response::setOutput(Theme::view('widget/footer_blocks', $data));
    }
    
    protected function validate() {
        if (!User::hasPermission('modify', 'widget/footer_blocks')) {
            $this->error['warning'] = $this->language->get('lang_error_permission');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
