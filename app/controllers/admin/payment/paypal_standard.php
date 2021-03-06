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

class PaypalStandard extends Controller {
    
    private $error = array();
    
    public function index() {
        $data = Theme::language('payment/paypal_standard');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('setting/setting');
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            SettingSetting::editSetting('paypal_standard', Request::post());
            Session::p()->data['success'] = Lang::get('lang_text_success');
            
            Response::redirect(Url::link('module/payment', '', 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }
        
        Breadcrumb::add('lang_text_payment', 'module/payment');
        Breadcrumb::add('lang_heading_title', 'payment/paypal_standard');
        
        $data['action'] = Url::link('payment/paypal_standard', '', 'SSL');
        
        $data['cancel'] = Url::link('module/payment', '', 'SSL');
        
        if (isset(Request::p()->post['paypal_standard_email'])) {
            $data['paypal_standard_email'] = Request::p()->post['paypal_standard_email'];
        } else {
            $data['paypal_standard_email'] = Config::get('paypal_standard_email');
        }
        
        if (isset(Request::p()->post['paypal_standard_test'])) {
            $data['paypal_standard_test'] = Request::p()->post['paypal_standard_test'];
        } else {
            $data['paypal_standard_test'] = Config::get('paypal_standard_test');
        }
        
        if (isset(Request::p()->post['paypal_standard_transaction'])) {
            $data['paypal_standard_transaction'] = Request::p()->post['paypal_standard_transaction'];
        } else {
            $data['paypal_standard_transaction'] = Config::get('paypal_standard_transaction');
        }
        
        if (isset(Request::p()->post['paypal_standard_debug'])) {
            $data['paypal_standard_debug'] = Request::p()->post['paypal_standard_debug'];
        } else {
            $data['paypal_standard_debug'] = Config::get('paypal_standard_debug');
        }
        
        if (isset(Request::p()->post['paypal_standard_total'])) {
            $data['paypal_standard_total'] = Request::p()->post['paypal_standard_total'];
        } else {
            $data['paypal_standard_total'] = Config::get('paypal_standard_total');
        }
        
        if (isset(Request::p()->post['paypal_standard_canceled_reversal_status_id'])) {
            $data['paypal_standard_canceled_reversal_status_id'] = Request::p()->post['paypal_standard_canceled_reversal_status_id'];
        } else {
            $data['paypal_standard_canceled_reversal_status_id'] = Config::get('paypal_standard_canceled_reversal_status_id');
        }
        
        if (isset(Request::p()->post['paypal_standard_completed_status_id'])) {
            $data['paypal_standard_completed_status_id'] = Request::p()->post['paypal_standard_completed_status_id'];
        } else {
            $data['paypal_standard_completed_status_id'] = Config::get('paypal_standard_completed_status_id');
        }
        
        if (isset(Request::p()->post['paypal_standard_denied_status_id'])) {
            $data['paypal_standard_denied_status_id'] = Request::p()->post['paypal_standard_denied_status_id'];
        } else {
            $data['paypal_standard_denied_status_id'] = Config::get('paypal_standard_denied_status_id');
        }
        
        if (isset(Request::p()->post['paypal_standard_expired_status_id'])) {
            $data['paypal_standard_expired_status_id'] = Request::p()->post['paypal_standard_expired_status_id'];
        } else {
            $data['paypal_standard_expired_status_id'] = Config::get('paypal_standard_expired_status_id');
        }
        
        if (isset(Request::p()->post['paypal_standard_failed_status_id'])) {
            $data['paypal_standard_failed_status_id'] = Request::p()->post['paypal_standard_failed_status_id'];
        } else {
            $data['paypal_standard_failed_status_id'] = Config::get('paypal_standard_failed_status_id');
        }
        
        if (isset(Request::p()->post['paypal_standard_pending_status_id'])) {
            $data['paypal_standard_pending_status_id'] = Request::p()->post['paypal_standard_pending_status_id'];
        } else {
            $data['paypal_standard_pending_status_id'] = Config::get('paypal_standard_pending_status_id');
        }
        
        if (isset(Request::p()->post['paypal_standard_processed_status_id'])) {
            $data['paypal_standard_processed_status_id'] = Request::p()->post['paypal_standard_processed_status_id'];
        } else {
            $data['paypal_standard_processed_status_id'] = Config::get('paypal_standard_processed_status_id');
        }
        
        if (isset(Request::p()->post['paypal_standard_refunded_status_id'])) {
            $data['paypal_standard_refunded_status_id'] = Request::p()->post['paypal_standard_refunded_status_id'];
        } else {
            $data['paypal_standard_refunded_status_id'] = Config::get('paypal_standard_refunded_status_id');
        }
        
        if (isset(Request::p()->post['paypal_standard_reversed_status_id'])) {
            $data['paypal_standard_reversed_status_id'] = Request::p()->post['paypal_standard_reversed_status_id'];
        } else {
            $data['paypal_standard_reversed_status_id'] = Config::get('paypal_standard_reversed_status_id');
        }
        
        if (isset(Request::p()->post['paypal_standard_voided_status_id'])) {
            $data['paypal_standard_voided_status_id'] = Request::p()->post['paypal_standard_voided_status_id'];
        } else {
            $data['paypal_standard_voided_status_id'] = Config::get('paypal_standard_voided_status_id');
        }
        
        Theme::model('locale/order_status');
        
        $data['order_statuses'] = LocaleOrderStatus::getOrderStatuses();
        
        if (isset(Request::p()->post['paypal_standard_geo_zone_id'])) {
            $data['paypal_standard_geo_zone_id'] = Request::p()->post['paypal_standard_geo_zone_id'];
        } else {
            $data['paypal_standard_geo_zone_id'] = Config::get('paypal_standard_geo_zone_id');
        }
        
        Theme::model('locale/geo_zone');
        
        $data['geo_zones'] = LocaleGeoZone::getGeoZones();
        
        if (isset(Request::p()->post['paypal_standard_status'])) {
            $data['paypal_standard_status'] = Request::p()->post['paypal_standard_status'];
        } else {
            $data['paypal_standard_status'] = Config::get('paypal_standard_status');
        }
        
        if (isset(Request::p()->post['paypal_standard_sort_order'])) {
            $data['paypal_standard_sort_order'] = Request::p()->post['paypal_standard_sort_order'];
        } else {
            $data['paypal_standard_sort_order'] = Config::get('paypal_standard_sort_order');
        }
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('payment/paypal_standard', $data));
    }
    
    private function validate() {
        if (!User::hasPermission('modify', 'payment/paypal_standard')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        if (!Request::p()->post['paypal_standard_email']) {
            $this->error['email'] = Lang::get('lang_error_email');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
