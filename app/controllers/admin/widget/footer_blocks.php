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

class FooterBlocks extends Controller {
    
    private $error = array();
    
    public function index() {
        $data = Theme::language('widget/footer_blocks');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('setting/setting');
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            SettingSetting::editSetting('footer_blocks', Request::post());
            Session::p()->data['success'] = Lang::get('lang_text_success');
            
            Response::redirect(Url::link('module/widget', '', 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        Breadcrumb::add('lang_text_widget', 'module/widget');
        Breadcrumb::add('lang_heading_title', 'widget/footer_blocks');
        
        $data['action'] = Url::link('widget/footer_blocks', '', 'SSL');
        $data['cancel'] = Url::link('module/widget', '', 'SSL');
        
        $data['widgets'] = array();
        
        if (isset(Request::p()->post['footer_blocks_widget'])) {
            $data['widgets'] = Request::p()->post['footer_blocks_widget'];
        } elseif (Config::get('footer_blocks_widget')) {
            $data['widgets'] = Config::get('footer_blocks_widget');
        }
        
        Theme::model('module/menu');
        
        $data['menus'] = array();
        
        $menus = ModuleMenu::getMenus();
        
        foreach ($menus as $menu):
            $data['menus'][] = array('menu_id' => $menu['menu_id'], 'name' => $menu['name']);
        endforeach;
        
        Theme::model('design/layout');
        
        $data['layouts'] = DesignLayout::getLayouts();
        
        Theme::loadjs('javascript/widget/footer_blocks', $data);
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('widget/footer_blocks', $data));
    }
    
    protected function validate() {
        if (!User::hasPermission('modify', 'widget/footer_blocks')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
