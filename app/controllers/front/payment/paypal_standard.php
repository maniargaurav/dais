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

namespace App\Controllers\Front\Payment;

use App\Controllers\Controller;

class PaypalStandard extends Controller {
    
    public function index() {
        $data = Theme::language('payment/paypal_standard');
        
        $data['testmode'] = Config::get('paypal_standard_test');
        
        if (!Config::get('paypal_standard_test')) {
            $data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
        } else {
            $data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
        
        Theme::model('checkout/order');
        
        $order_info = CheckoutOrder::getOrder(Session::p()->data['order_id']);
        
        if ($order_info) {
            $data['business'] = Config::get('paypal_standard_email');
            $data['item_name'] = html_entity_decode(Config::get('config_name'), ENT_QUOTES, 'UTF-8');
            
            $data['products'] = array();
            
            foreach (Cart::getProducts() as $product) {
                $option_data = array();
                
                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['option_value'];
                    } else {
                        $filename = Encryption::decrypt($option['option_value']);
                        
                        $value = Encode::substr($filename, 0, Encode::strrpos($filename, '.'));
                    }
                    
                    $option_data[] = array('name' => $option['name'], 'value' => (Encode::strlen($value) > 20 ? Encode::substr($value, 0, 20) . '..' : $value));
                }
                
                $data['products'][] = array('name' => $product['name'], 'model' => $product['model'], 'price' => Currency::format($product['price'], $order_info['currency_code'], false, false), 'quantity' => $product['quantity'], 'option' => $option_data, 'weight' => $product['weight']);
            }
            
            $data['discount_amount_cart'] = 0;
            
            $total = Currency::format($order_info['total'] - Cart::getSubTotal(), $order_info['currency_code'], false, false);
            
            if ($total > 0) {
                $data['products'][] = array('name' => Lang::get('lang_text_total'), 'model' => '', 'price' => $total, 'quantity' => 1, 'option' => array(), 'weight' => 0);
            } else {
                $data['discount_amount_cart']-= $total;
            }
            
            $data['currency_code'] = $order_info['currency_code'];
            $data['first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
            $data['last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
            $data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
            $data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
            $data['city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
            $data['zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
            $data['country'] = $order_info['payment_iso_code_2'];
            $data['email'] = $order_info['email'];
            $data['invoice'] = Session::p()->data['order_id'] . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
            $data['lc'] = Session::p()->data['language'];
            $data['return'] = Url::link('checkout/success');
            $data['notify_url'] = Url::link('payment/paypal_standard/callback', '', 'SSL');
            $data['cancel_return'] = Url::link('checkout/checkout', '', 'SSL');
            
            if (!Config::get('paypal_standard_transaction')) {
                $data['paymentaction'] = 'authorization';
            } else {
                $data['paymentaction'] = 'sale';
            }
            
            $data['custom'] = Session::p()->data['order_id'];
            
            Theme::loadjs('javascript/payment/paypal_standard', $data);
            
            $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
            
            $data['javascript'] = Theme::controller('common/javascript');
            
            return View::make('payment/paypal_standard', $data);
        }
    }
    
    public function callback() {
        if (isset(Request::p()->post['custom'])) {
            $order_id = Request::p()->post['custom'];
        } else {
            $order_id = 0;
        }
        
        Theme::model('checkout/order');
        
        $order_info = CheckoutOrder::getOrder($order_id);
        
        if ($order_info) {
            $request = 'cmd=_notify-validate';
            
            foreach (Request::post() as $key => $value) {
                $request.= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
            }
            
            if (!Config::get('paypal_standard_test')) {
                $curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
            } else {
                $curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
            }
            
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($curl);
            
            if (!$response) {
                Log::write('PP_STANDARD :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
            }
            
            if (Config::get('paypal_standard_debug')) {
                Log::write('PP_STANDARD :: IPN REQUEST: ' . $request);
                Log::write('PP_STANDARD :: IPN RESPONSE: ' . $response);
            }
            
            if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset(Request::p()->post['payment_status'])) {
                $order_status_id = Config::get('config_order_status_id');
                
                switch (Request::p()->post['payment_status']) {
                    case 'Canceled_Reversal':
                        $order_status_id = Config::get('paypal_standard_canceled_reversal_status_id');
                        break;

                    case 'Completed':
                        if ((strtolower(Request::p()->post['receiver_email']) == strtolower(Config::get('paypal_standard_email'))) && ((float)Request::p()->post['mc_gross'] == Currency::format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false))) {
                            $order_status_id = Config::get('paypal_standard_completed_status_id');
                        } else {
                            Log::write('PP_STANDARD :: RECEIVER EMAIL MISMATCH! ' . strtolower(Request::p()->post['receiver_email']));
                        }
                        break;

                    case 'Denied':
                        $order_status_id = Config::get('paypal_standard_denied_status_id');
                        break;

                    case 'Expired':
                        $order_status_id = Config::get('paypal_standard_expired_status_id');
                        break;

                    case 'Failed':
                        $order_status_id = Config::get('paypal_standard_failed_status_id');
                        break;

                    case 'Pending':
                        $order_status_id = Config::get('paypal_standard_pending_status_id');
                        break;

                    case 'Processed':
                        $order_status_id = Config::get('paypal_standard_processed_status_id');
                        break;

                    case 'Refunded':
                        $order_status_id = Config::get('paypal_standard_refunded_status_id');
                        break;

                    case 'Reversed':
                        $order_status_id = Config::get('paypal_standard_reversed_status_id');
                        break;

                    case 'Voided':
                        $order_status_id = Config::get('paypal_standard_voided_status_id');
                        break;
                }
                
                if (!$order_info['order_status_id']) {
                    
                    Theme::listen(__CLASS__, __FUNCTION__);
                    
                    CheckoutOrder::confirm($order_id, $order_status_id);
                } else {
                    Theme::listen(__CLASS__, __FUNCTION__);
                    
                    CheckoutOrder::update($order_id, $order_status_id);
                }
            } else {
                Theme::listen(__CLASS__, __FUNCTION__);
                
                CheckoutOrder::confirm($order_id, Config::get('config_order_status_id'));
            }
            
            curl_close($curl);
        }
    }
}
