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

namespace App\Controllers\Admin\Total;

use App\Controllers\Controller;

class Reward extends Controller {
    
    private $error = array();
    
    public function index() {
        $data = Theme::language('total/reward');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('setting/setting');
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            SettingSetting::editSetting('reward', Request::post());
            Session::p()->data['success'] = Lang::get('lang_text_success');
            
            Response::redirect(Url::link('module/total', '', 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        Breadcrumb::add('lang_text_total', 'module/total');
        Breadcrumb::add('lang_heading_title', 'total/reward');
        
        $data['action'] = Url::link('total/reward', '', 'SSL');
        $data['cancel'] = Url::link('module/total', '', 'SSL');
        
        if (isset(Request::p()->post['reward_status'])) {
            $data['reward_status'] = Request::p()->post['reward_status'];
        } else {
            $data['reward_status'] = Config::get('reward_status');
        }
        
        if (isset(Request::p()->post['reward_sort_order'])) {
            $data['reward_sort_order'] = Request::p()->post['reward_sort_order'];
        } else {
            $data['reward_sort_order'] = Config::get('reward_sort_order');
        }
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::render('total/reward', $data));
    }
    
    protected function validate() {
        if (!User::hasPermission('modify', 'total/reward')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
