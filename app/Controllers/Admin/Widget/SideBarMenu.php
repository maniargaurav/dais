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

namespace App\Controllers\Admin\Widget;
use App\Controllers\Controller;

class SideBarMenu extends Controller {
    private $error = array();
    
    public function index() {
        $data = Theme::language('widget/side_bar_menu');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('sidebarmenu', $this->request->post);
            $this->session->data['success'] = Lang::get('lang_text_success');
            
            Response::redirect(Url::link('module/widget', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        Breadcrumb::add('lang_text_widget', 'module/widget');
        Breadcrumb::add('lang_heading_title', 'widget/side_bar_menu');
        
        $data['action'] = Url::link('widget/side_bar_menu', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = Url::link('module/widget', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['widgets'] = array();
        
        if (isset($this->request->post['sidebarmenu_widget'])) {
            $data['widgets'] = $this->request->post['sidebarmenu_widget'];
        } elseif (Config::get('sidebarmenu_widget')) {
            $data['widgets'] = Config::get('sidebarmenu_widget');
        }
        
        Theme::model('module/menu');
        
        $data['menus'] = array();
        
        $menus = $this->model_module_menu->getMenus();
        
        foreach ($menus as $menu):
            $data['menus'][] = array('menu_id' => $menu['menu_id'], 'name' => $menu['name']);
        endforeach;
        
        Theme::model('design/layout');
        
        $data['layouts'] = $this->model_design_layout->getLayouts();
        
        Theme::loadjs('javascript/widget/side_bar_menu', $data);
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::render_controllers($data);
        
        Response::setOutput(Theme::view('widget/side_bar_menu', $data));
    }
    
    protected function validate() {
        if (!User::hasPermission('modify', 'widget/sidebarmenu')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
