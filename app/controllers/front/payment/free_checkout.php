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

class FreeCheckout extends Controller {
    
    public function index() {
        $data = Theme::language('payment/free_checkout');
        
        $data['continue'] = Url::link('checkout/success');
        
        Theme::loadjs('javascript/payment/free_checkout', $data);
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data['javascript'] = Theme::controller('common/javascript');
        
        return View::make('payment/free_checkout', $data);
    }
    
    public function confirm() {
        Theme::model('checkout/order');
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        CheckoutOrder::confirm(Session::p()->data['order_id'], Config::get('free_checkout_order_status_id'));
    }
}
