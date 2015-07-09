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

namespace Admin\Controller\Payment;

use Dais\Engine\Controller;
use Dais\Engine\Action;

class PayflowIframe extends Controller {
    private $error = array();
    
    public function index() {
        $data = Theme::language('payment/payflow_iframe');
        Theme::setTitle($this->language->get('lang_heading_title'));
        Theme::model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payflow_iframe', $this->request->post);
            $this->session->data['success'] = $this->language->get('lang_text_success');
            
            Response::redirect($this->url->link('module/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['vendor'])) {
            $data['error_vendor'] = $this->error['vendor'];
        } else {
            $data['error_vendor'] = '';
        }
        
        if (isset($this->error['user'])) {
            $data['error_user'] = $this->error['user'];
        } else {
            $data['error_user'] = '';
        }
        
        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }
        
        if (isset($this->error['partner'])) {
            $data['error_partner'] = $this->error['partner'];
        } else {
            $data['error_partner'] = '';
        }
        
        $this->breadcrumb->add('lang_text_payment', 'module/payment');
        $this->breadcrumb->add('lang_heading_title', 'payment/payflow_iframe');
        
        $data['action'] = $this->url->link('payment/payflow_iframe', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['cancel'] = $this->url->link('module/payment', 'token=' . $this->session->data['token'], 'SSL');
        
        if (isset($this->request->post['payflow_iframe_vendor'])) {
            $data['payflow_iframe_vendor'] = $this->request->post['payflow_iframe_vendor'];
        } else {
            $data['payflow_iframe_vendor'] = Config::get('payflow_iframe_vendor');
        }
        
        if (isset($this->request->post['payflow_iframe_user'])) {
            $data['payflow_iframe_user'] = $this->request->post['payflow_iframe_user'];
        } else {
            $data['payflow_iframe_user'] = Config::get('payflow_iframe_user');
        }
        
        if (isset($this->request->post['payflow_iframe_password'])) {
            $data['payflow_iframe_password'] = $this->request->post['payflow_iframe_password'];
        } else {
            $data['payflow_iframe_password'] = Config::get('payflow_iframe_password');
        }
        
        if (isset($this->request->post['payflow_iframe_partner'])) {
            $data['payflow_iframe_partner'] = $this->request->post['payflow_iframe_partner'];
        } else {
            $data['payflow_iframe_partner'] = Config::get('payflow_iframe_partner');
        }
        
        if (isset($this->request->post['payflow_iframe_transaction_method'])) {
            $data['payflow_iframe_transaction_method'] = $this->request->post['payflow_iframe_transaction_method'];
        } else {
            $data['payflow_iframe_transaction_method'] = Config::get('payflow_iframe_transaction_method');
        }
        
        if (isset($this->request->post['payflow_iframe_test'])) {
            $data['payflow_iframe_test'] = $this->request->post['payflow_iframe_test'];
        } else {
            $data['payflow_iframe_test'] = Config::get('payflow_iframe_test');
        }
        
        if (isset($this->request->post['payflow_iframe_total'])) {
            $data['payflow_iframe_total'] = $this->request->post['payflow_iframe_total'];
        } else {
            $data['payflow_iframe_total'] = Config::get('payflow_iframe_total');
        }
        
        Theme::model('localization/order_status');
        $data['order_statuses'] = $this->model_localization_order_status->getOrderStatuses();
        
        if (isset($this->request->post['payflow_iframe_order_status_id'])) {
            $data['payflow_iframe_order_status_id'] = $this->request->post['payflow_iframe_order_status_id'];
        } else {
            $data['payflow_iframe_order_status_id'] = Config::get('payflow_iframe_order_status_id');
        }
        
        if (isset($this->request->post['payflow_iframe_geo_zone_id'])) {
            $data['payflow_iframe_geo_zone_id'] = $this->request->post['payflow_iframe_geo_zone_id'];
        } else {
            $data['payflow_iframe_geo_zone_id'] = Config::get('payflow_iframe_geo_zone_id');
        }
        
        Theme::model('localization/geo_zone');
        
        $data['geo_zones'] = $this->model_localization_geo_zone->getGeoZones();
        
        if (isset($this->request->post['payflow_iframe_status'])) {
            $data['payflow_iframe_status'] = $this->request->post['payflow_iframe_status'];
        } else {
            $data['payflow_iframe_status'] = Config::get('payflow_iframe_status');
        }
        
        if (isset($this->request->post['payflow_iframe_sort_order'])) {
            $data['payflow_iframe_sort_order'] = $this->request->post['payflow_iframe_sort_order'];
        } else {
            $data['payflow_iframe_sort_order'] = Config::get('payflow_iframe_sort_order');
        }
        
        if (isset($this->request->post['payflow_iframe_checkout_method'])) {
            $data['payflow_iframe_checkout_method'] = $this->request->post['payflow_iframe_checkout_method'];
        } else {
            $data['payflow_iframe_checkout_method'] = Config::get('payflow_iframe_checkout_method');
        }
        
        if (isset($this->request->post['payflow_iframe_debug'])) {
            $data['payflow_iframe_debug'] = $this->request->post['payflow_iframe_debug'];
        } else {
            $data['payflow_iframe_debug'] = Config::get('payflow_iframe_debug');
        }
        
        $data['cancel_url'] = Config::get('https.public') . 'payment/payflow_iframe/pp_cancel';
        $data['error_url'] = Config::get('https.public') . 'payment/payflow_iframe/pp_error';
        $data['return_url'] = Config::get('https.public') . 'payment/payflow_iframe/pp_return';
        $data['post_url'] = Config::get('https.public') . 'payment/payflow_iframe/pp_post';
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::render_controllers($data);
        
        Response::setOutput(Theme::view('payment/payflow_iframe', $data));
    }
    
    public function install() {
        Theme::model('payment/payflow_iframe');
        $this->model_payment_payflow_iframe->install();
        
        Theme::listen(__CLASS__, __FUNCTION__);
    }
    
    public function uninstall() {
        Theme::model('payment/payflow_iframe');
        $this->model_payment_payflow_iframe->uninstall();
        
        Theme::listen(__CLASS__, __FUNCTION__);
    }
    
    public function refund() {
        Theme::model('payment/payflow_iframe');
        Theme::model('sale/order');
        $data = Theme::language('payment/payflow_iframe');
        
        $transaction = $this->model_payment_payflow_iframe->getTransaction($this->request->get['transaction_reference']);
        
        if ($transaction) {
            Theme::setTitle($this->language->get('lang_heading_refund'));
            
            $this->breadcrumb->add('lang_text_payment', 'module/payment');
            $this->breadcrumb->add('lang_heading_title', 'payment/payflow_iframe');
            $this->breadcrumb->add('lang_heading_refund', 'payment/payflow_iframe/refund', 'transaction_reference=' . $this->request->get['transaction_reference']);
            
            $data['transaction_reference'] = $transaction['transaction_reference'];
            $data['transaction_amount'] = number_format($transaction['amount'], 2);
            $data['cancel'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $transaction['order_id'], 'SSL');
            
            $data['token'] = $this->session->data['token'];
            
            Theme::loadjs('javascript/payment/payflow_iframe_refund', $data);
            
            $data = Theme::render_controllers($data);
            
            Theme::listen(__CLASS__, __FUNCTION__);
            
            Response::setOutput(Theme::view('payment/payflow_iframe_refund', $data));
        } else {
            return new Action('error/not_found');
        }
    }
    
    public function do_refund() {
        Theme::model('payment/payflow_iframe');
        $this->language->load('payment/payflow_iframe');
        $json = array();
        
        if (isset($this->request->post['transaction_reference']) && isset($this->request->post['amount'])) {
            
            $transaction = $this->model_payment_payflow_iframe->getTransaction($this->request->post['transaction_reference']);
            
            if ($transaction) {
                $call_data = array('TRXTYPE' => 'C', 'TENDER' => 'C', 'ORIGID' => $transaction['transaction_reference'], 'AMT' => $this->request->post['amount'],);
                
                $result = $this->model_payment_payflow_iframe->call($call_data);
                
                if ($result['RESULT'] == 0) {
                    $json['success'] = $this->language->get('lang_text_refund_issued');
                    
                    $filter = array('order_id' => $transaction['order_id'], 'type' => 'C', 'transaction_reference' => $result['PNREF'], 'amount' => $this->request->post['amount'],);
                    
                    $this->model_payment_payflow_iframe->addTransaction($filter);
                } else {
                    $json['error'] = $result['RESPMSG'];
                }
            } else {
                $json['error'] = $this->language->get('lang_error_missing_order');
            }
        } else {
            $json['error'] = $this->language->get('lang_error_missing_data');
        }
        
        $json = Theme::listen(__CLASS__, __FUNCTION__, $json);
        
        Response::setOutput(json_encode($json));
    }
    
    public function capture() {
        Theme::model('payment/payflow_iframe');
        Theme::model('sale/order');
        $this->language->load('payment/payflow_iframe');
        
        $json = array();
        
        if (isset($this->request->post['order_id']) && isset($this->request->post['amount']) && isset($this->request->post['complete'])) {
            $order_id = $this->request->post['order_id'];
            $paypal_order = $this->model_payment_payflow_iframe->getOrder($order_id);
            $order_info = $this->model_sale_order->getOrder($order_id);
            
            if ($paypal_order && $order_info) {
                if ($this->request->post['complete'] == 1) {
                    $complete = 'Y';
                } else {
                    $complete = 'N';
                }
                
                $call_data = array('TRXTYPE' => 'D', 'TENDER' => 'C', 'ORIGID' => $paypal_order['transaction_reference'], 'AMT' => $this->request->post['amount'], 'CAPTURECOMPLETE' => $complete,);
                
                $result = $this->model_payment_payflow_iframe->call($call_data);
                
                if ($result['RESULT'] == 0) {
                    
                    $filter = array('order_id' => $order_id, 'type' => 'D', 'transaction_reference' => $result['PNREF'], 'amount' => $this->request->post['amount'],);
                    
                    $this->model_payment_payflow_iframe->addTransaction($filter);
                    $this->model_payment_payflow_iframe->updateOrderStatus($order_id, $this->request->post['complete']);
                    
                    $actions = array();
                    
                    $actions[] = array('title' => $this->language->get('lang_text_capture'), 'href' => $this->url->link('payment/payflow_iframe/refund', 'transaction_reference=' . $result['PNREF'] . '&token=' . $this->session->data['token']),);
                    
                    $json['success'] = array('transaction_type' => $this->language->get('lang_text_capture'), 'transaction_reference' => $result['PNREF'], 'time' => date('Y-m-d H:i:s'), 'amount' => number_format($this->request->post['amount'], 2), 'actions' => $actions,);
                } else {
                    $json['error'] = $result['RESPMSG'];
                }
            } else {
                $json['error'] = $this->language->get('lang_error_missing_order');
            }
        } else {
            $json['error'] = $this->language->get('lang_error_missing_data');
        }
        
        $json = Theme::listen(__CLASS__, __FUNCTION__, $json);
        
        Response::setOutput(json_encode($json));
    }
    
    public function void() {
        Theme::model('payment/payflow_iframe');
        $this->language->load('payment/payflow_iframe');
        
        $json = array();
        
        if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
            $order_id = $this->request->post['order_id'];
            $paypal_order = $this->model_payment_payflow_iframe->getOrder($order_id);
            
            if ($paypal_order) {
                $call_data = array('TRXTYPE' => 'V', 'TENDER' => 'C', 'ORIGID' => $paypal_order['transaction_reference'],);
                
                $result = $this->model_payment_payflow_iframe->call($call_data);
                
                if ($result['RESULT'] == 0) {
                    $json['success'] = $this->language->get('lang_text_void_success');
                    $this->model_payment_payflow_iframe->updateOrderStatus($order_id, 1);
                    
                    $filter = array('order_id' => $order_id, 'type' => 'V', 'transaction_reference' => $result['PNREF'], 'amount' => '',);
                    
                    $this->model_payment_payflow_iframe->addTransaction($filter);
                    $this->model_payment_payflow_iframe->updateOrderStatus($order_id, 1);
                    
                    $json['success'] = array('transaction_type' => $this->language->get('lang_text_void'), 'transaction_reference' => $result['PNREF'], 'time' => date('Y-m-d H:i:s'), 'amount' => '0.00',);
                } else {
                    $json['error'] = $result['RESPMSG'];
                }
            } else {
                $json['error'] = $this->language->get('lang_error_missing_order');
            }
        } else {
            $json['error'] = $this->language->get('lang_error_missing_data');
        }
        
        $json = Theme::listen(__CLASS__, __FUNCTION__, $json);
        
        Response::setOutput(json_encode($json));
    }
    
    public function orderAction() {
        Theme::model('payment/payflow_iframe');
        $data = Theme::language('payment/payflow_iframe');
        
        $order_id = $this->request->get['order_id'];
        
        $paypal_order = $this->model_payment_payflow_iframe->getOrder($order_id);
        
        if ($paypal_order) {
            $data['complete'] = $paypal_order['complete'];
            $data['order_id'] = $this->request->get['order_id'];
            $data['token'] = $this->request->get['token'];
            
            $data['transactions'] = array();
            
            $transactions = $this->model_payment_payflow_iframe->getTransactions($order_id);
            
            foreach ($transactions as $transaction) {
                $actions = array();
                
                switch ($transaction['transaction_type']) {
                    case 'V':
                        $transaction_type = $this->language->get('lang_text_void');
                        break;

                    case 'S':
                        $transaction_type = $this->language->get('lang_text_sale');
                        
                        $actions[] = array('title' => $this->language->get('lang_text_refund'), 'href' => $this->url->link('payment/payflow_iframe/refund', 'transaction_reference=' . $transaction['transaction_reference'] . '&token=' . $this->session->data['token']),);
                        
                        break;

                    case 'D':
                        $transaction_type = $this->language->get('lang_text_capture');
                        
                        $actions[] = array('title' => $this->language->get('lang_text_refund'), 'href' => $this->url->link('payment/payflow_iframe/refund', 'transaction_reference=' . $transaction['transaction_reference'] . '&token=' . $this->session->data['token']),);
                        
                        break;

                    case 'A':
                        $transaction_type = $this->language->get('lang_text_authorise');
                        break;

                    case 'C':
                        $transaction_type = $this->language->get('lang_text_refund'); //
                        break;

                    default:
                        $transaction_type = '';
                        break;
                }
                
                $data['transactions'][] = array('transaction_reference' => $transaction['transaction_reference'], 'transaction_type' => $transaction_type, 'time' => $transaction['time'], 'amount' => $transaction['amount'], 'actions' => $actions,);
            }
            
            Theme::loadjs('javascript/payment/payflow_iframe_order', $data);
            
            $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
            
            $data['javascript'] = Theme::controller('common/javascript');
            
            Response::setOutput(Theme::view('payment/payflow_iframe_order', $data));
        }
    }
    
    protected function validate() {
        if (!User::hasPermission('modify', 'payment/payflow_iframe')) {
            $this->error['warning'] = $this->language->get('lang_error_permission');
        }
        
        if (!$this->request->post['payflow_iframe_vendor']) {
            $this->error['vendor'] = $this->language->get('lang_error_vendor');
        }
        
        if (!$this->request->post['payflow_iframe_user']) {
            $this->error['user'] = $this->language->get('lang_error_user');
        }
        
        if (!$this->request->post['payflow_iframe_password']) {
            $this->error['password'] = $this->language->get('lang_error_password');
        }
        
        if (!$this->request->post['payflow_iframe_partner']) {
            $this->error['partner'] = $this->language->get('lang_error_partner');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
