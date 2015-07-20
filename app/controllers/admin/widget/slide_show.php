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

class SlideShow extends Controller {
    
    private $error = array();
    
    public function index() {
        $data = Theme::language('widget/slide_show');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('setting/setting');
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            SettingSetting::editSetting('slide_show', Request::post());
            Session::p()->data['success'] = Lang::get('lang_text_success');
            
            Response::redirect(Url::link('module/widget', '', 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['dimension'])) {
            $data['error_dimension'] = $this->error['dimension'];
        } else {
            $data['error_dimension'] = array();
        }
        
        Breadcrumb::add('lang_text_widget', 'module/widget');
        Breadcrumb::add('lang_heading_title', 'widget/slide_show');
        
        $data['action'] = Url::link('widget/slide_show', '', 'SSL');
        $data['cancel'] = Url::link('module/widget', '', 'SSL');
        
        $data['widgets'] = array();
        
        if (isset(Request::p()->post['slide_show_widget'])) {
            $data['widgets'] = Request::p()->post['slide_show_widget'];
        } elseif (Config::get('slide_show_widget')) {
            $data['widgets'] = Config::get('slide_show_widget');
        }
        
        Theme::model('design/layout');
        
        $data['layouts'] = DesignLayout::getLayouts();
        
        Theme::model('design/banner');
        
        $data['banners'] = DesignBanner::getBanners();
        
        Theme::loadjs('javascript/widget/slide_show', $data);
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('widget/slide_show', $data));
    }
    
    protected function validate() {
        if (!User::hasPermission('modify', 'widget/slide_show')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        if (isset(Request::p()->post['slide_show_widget'])) {
            foreach (Request::p()->post['slide_show_widget'] as $key => $value) {
                if (!$value['width'] || !$value['height']) {
                    $this->error['dimension'][$key] = Lang::get('lang_error_dimension');
                }
            }
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
