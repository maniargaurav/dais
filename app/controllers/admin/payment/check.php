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

namespace App\Controllers\Admin\Payment;

use App\Controllers\Controller;

class Check extends Controller {
    
    private $error = array();
    
    public function index() {
        $data = Theme::language('payment/check');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            SettingSetting::editSetting('check', $this->request->post);
            $this->session->data['success'] = Lang::get('lang_text_success');
            
            Response::redirect(Url::link('module/payment', '', 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['payable'])) {
            $data['error_payable'] = $this->error['payable'];
        } else {
            $data['error_payable'] = '';
        }
        
        Breadcrumb::add('lang_text_payment', 'module/payment');
        Breadcrumb::add('lang_heading_title', 'payment/check');
        
        $data['action'] = Url::link('payment/check', '', 'SSL');
        
        $data['cancel'] = Url::link('module/payment', '', 'SSL');
        
        if (isset($this->request->post['check_payable'])) {
            $data['check_payable'] = $this->request->post['check_payable'];
        } else {
            $data['check_payable'] = Config::get('check_payable');
        }
        
        if (isset($this->request->post['check_total'])) {
            $data['check_total'] = $this->request->post['check_total'];
        } else {
            $data['check_total'] = Config::get('check_total');
        }
        
        if (isset($this->request->post['check_order_status_id'])) {
            $data['check_order_status_id'] = $this->request->post['check_order_status_id'];
        } else {
            $data['check_order_status_id'] = Config::get('check_order_status_id');
        }
        
        Theme::model('locale/order_status');
        
        $data['order_statuses'] = LocaleOrderStatus::getOrderStatuses();
        
        if (isset($this->request->post['check_geo_zone_id'])) {
            $data['check_geo_zone_id'] = $this->request->post['check_geo_zone_id'];
        } else {
            $data['check_geo_zone_id'] = Config::get('check_geo_zone_id');
        }
        
        Theme::model('locale/geo_zone');
        
        $data['geo_zones'] = LocaleGeoZone::getGeoZones();
        
        if (isset($this->request->post['check_status'])) {
            $data['check_status'] = $this->request->post['check_status'];
        } else {
            $data['check_status'] = Config::get('check_status');
        }
        
        if (isset($this->request->post['check_sort_order'])) {
            $data['check_sort_order'] = $this->request->post['check_sort_order'];
        } else {
            $data['check_sort_order'] = Config::get('check_sort_order');
        }
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::render('payment/check', $data));
    }
    
    protected function validate() {
        if (!User::hasPermission('modify', 'payment/check')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        if (!$this->request->post['check_payable']) {
            $this->error['payable'] = Lang::get('lang_error_payable');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
