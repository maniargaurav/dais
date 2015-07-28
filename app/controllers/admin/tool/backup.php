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

namespace App\Controllers\Admin\Tool;

use App\Controllers\Controller;

class Backup extends Controller {
    
    private $error = array();
    
    public function index() {
        $data = Theme::language('tool/backup');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('tool/backup');
        
        if (Request::p()->server['REQUEST_METHOD'] == 'POST' && User::hasPermission('modify', 'tool/backup')) {
            if (is_uploaded_file(Request::p()->files['import']['tmp_name'])) {
                $content = file_get_contents(Request::p()->files['import']['tmp_name']);
            } else {
                $content = false;
            }
            
            if ($content) {
                ToolBackup::restore($content);
                Session::p()->data['success'] = Lang::get('lang_text_success');
                
                Response::redirect(Url::link('tool/backup', '', 'SSL'));
            } else {
                $this->error['warning'] = Lang::get('lang_error_empty');
            }
        }
        
        if (isset(Session::p()->data['error'])) {
            $data['error_warning'] = Session::p()->data['error'];
            
            unset(Session::p()->data['error']);
        } elseif (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset(Session::p()->data['success'])) {
            $data['success'] = Session::p()->data['success'];
            
            unset(Session::p()->data['success']);
        } else {
            $data['success'] = '';
        }
        
        Breadcrumb::add('lang_heading_title', 'tool/backup');
        
        $data['restore'] = Url::link('tool/backup', '', 'SSL');
        $data['backup'] = Url::link('tool/backup/backup', '', 'SSL');
        $data['tables'] = ToolBackup::getTables();
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('tool/backup', $data));
    }
    
    public function backup() {
        Lang::load('tool/backup');
        
        if (!isset(Request::p()->post['backup'])) {
            Session::p()->data['error'] = Lang::get('lang_error_backup');
            
            Theme::listen(__CLASS__, __FUNCTION__);
            
            Response::redirect(Url::link('tool/backup', '', 'SSL'));
        } elseif (User::hasPermission('modify', 'tool/backup')) {
            Response::addheader('Pragma: public');
            Response::addheader('Expires: 0');
            Response::addheader('Content-Description: File Transfer');
            Response::addheader('Content-Type: application/octet-stream');
            Response::addheader('Content-Disposition: attachment; filename=' . date('Y-m-d_H-i-s', time()) . '_backup.sql');
            Response::addheader('Content-Transfer-Encoding: binary');
            
            Theme::model('tool/backup');
            
            Theme::listen(__CLASS__, __FUNCTION__);
            
            Response::setOutput(ToolBackup::backup(Request::p()->post['backup']));
        } else {
            Session::p()->data['error'] = Lang::get('lang_error_permission');
            
            Theme::listen(__CLASS__, __FUNCTION__);
            
            Response::redirect(Url::link('tool/backup', '', 'SSL'));
        }
    }
}
