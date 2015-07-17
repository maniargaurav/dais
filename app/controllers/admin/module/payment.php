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

namespace App\Controllers\Admin\Module;

use App\Controllers\Controller;
use Dais\Support\Naming;

class Payment extends Controller {
    
    public function index() {
        $data = Theme::language('module/payment');
        Theme::setTitle(Lang::get('lang_heading_payment'));
        
        Breadcrumb::add('lang_heading_payment', 'module/payment');
        
        if (isset(Session::p()->data['success'])) {
            $data['success'] = Session::p()->data['success'];
            
            unset(Session::p()->data['success']);
        } else {
            $data['success'] = '';
        }
        
        if (isset(Session::p()->data['error'])) {
            $data['error'] = Session::p()->data['error'];
            
            unset(Session::p()->data['error']);
        } else {
            $data['error'] = '';
        }
        
        Theme::model('setting/module');
        
        $modules = SettingModule::getInstalled('payment');
        
        foreach ($modules as $key => $value) {
            $theme_file = Theme::getPath() . 'controller/payment/' . $value . '.php';
            $core_file  = Config::get('path.application') . 'payment/' . $value . '.php';
            
            if (!is_readable($theme_file) && !is_readable($core_file)) {
                SettingModule::uninstall('payment', $value);
                
                unset($modules[$key]);
            }
        }
        
        $data['modules'] = array();
        
        $files = Theme::getFiles('payment');
        
        if ($files) {
            foreach ($files as $file) {
                $module = strtolower(basename($file, '.php'));
                
                $data = Theme::language('payment/' . $module, $data);
                
                $action = array();
                
                if (!in_array($module, $modules)) {
                    $action[] = array('text' => Lang::get('lang_text_install'), 'href' => Url::link('module/payment/install', '' . 'module=' . $module, 'SSL'));
                } else {
                    $action[] = array('text' => Lang::get('lang_text_edit'), 'href' => Url::link('payment/' . $module . '', '', 'SSL'));
                    
                    $action[] = array('text' => Lang::get('lang_text_uninstall'), 'href' => Url::link('module/payment/uninstall', '' . 'module=' . $module, 'SSL'));
                }
                
                $data['modules'][] = array('name' => Lang::get('lang_heading_title'), 'status' => Config::get($module . '_status') ? Lang::get('lang_text_enabled') : Lang::get('lang_text_disabled'), 'sort_order' => Config::get($module . '_sort_order'), 'action' => $action);
            }
        }
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::render('module/payment', $data));
    }
    
    public function install() {
        Lang::load('module/payment');
        
        if (!User::hasPermission('modify', 'module/payment')) {
            Session::p()->data['error'] = Lang::get('lang_error_permission');
            
            Theme::listen(__CLASS__, __FUNCTION__);
            
            Response::redirect(Url::link('module/payment', '', 'SSL'));
        } else {
            Theme::model('setting/module');
            
            SettingModule::install('payment', Request::p()->get['module']);
            
            Theme::model('people/user_group');
            
            PeopleUserGroup::addPermission(User::getId(), 'access', 'payment/' . Request::p()->get['module']);
            PeopleUserGroup::addPermission(User::getId(), 'modify', 'payment/' . Request::p()->get['module']);
            
            $base_path  = Config::get('path.application') . 'payment' . SEP;
            $theme_path = Config::get('path.theme') . Config::get('theme.name') . SEP . 'controller' . SEP . 'payment' . SEP;
            
            if (is_readable($file = $theme_path . Request::p()->get['module'] . '.php')):
                $class = Naming::class_from_filename($file);
            else:
                $class = Naming::class_from_filename($base_path . Request::p()->get['module'] . '.php');
            endif;
            
            $class = new $class;
            
            if (method_exists($class, 'install')) {
                $class->install();
            }
            
            Theme::listen(__CLASS__, __FUNCTION__);
            
            Response::redirect(Url::link('module/payment', '', 'SSL'));
        }
    }
    
    public function uninstall() {
        Lang::load('module/payment');
        
        if (!User::hasPermission('modify', 'module/payment')) {
            Session::p()->data['error'] = Lang::get('lang_error_permission');
            
            Theme::listen(__CLASS__, __FUNCTION__);
            
            Response::redirect(Url::link('module/payment', '', 'SSL'));
        } else {
            Theme::model('setting/module');
            Theme::model('setting/setting');
            
            SettingModule::uninstall('payment', Request::p()->get['module']);
            SettingSetting::deleteSetting(Request::p()->get['module']);
            
            $base_path  = Config::get('path.application') . 'payment' . SEP;
            $theme_path = Config::get('path.theme') . Config::get('theme.name') . SEP . 'controller' . SEP . 'payment' . SEP;
            
            if (is_readable($file = $theme_path . Request::p()->get['module'] . '.php')):
                $class = Naming::class_from_filename($file);
            else:
                $class = Naming::class_from_filename($base_path . Request::p()->get['module'] . '.php');
            endif;
            
            $class = new $class;
            
            if (method_exists($class, 'uninstall')) {
                $class->uninstall();
            }
            
            Theme::listen(__CLASS__, __FUNCTION__);
            
            Response::redirect(Url::link('module/payment', '', 'SSL'));
        }
    }
}
