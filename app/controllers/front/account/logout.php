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


namespace App\Controllers\Front\Account;

use App\Controllers\Controller;

class Logout extends Controller {
    
    public function index() {
        if (Customer::isLogged()) {
            
            $customer_id = Customer::getId();
            
            Customer::logout();
            Cart::clear();
            
            unset(Session::p()->data['wishlist']);
            unset(Session::p()->data['shipping_address_id']);
            unset(Session::p()->data['shipping_country_id']);
            unset(Session::p()->data['shipping_zone_id']);
            unset(Session::p()->data['shipping_postcode']);
            unset(Session::p()->data['shipping_method']);
            unset(Session::p()->data['shipping_methods']);
            unset(Session::p()->data['payment_address_id']);
            unset(Session::p()->data['payment_country_id']);
            unset(Session::p()->data['payment_zone_id']);
            unset(Session::p()->data['payment_method']);
            unset(Session::p()->data['payment_methods']);
            unset(Session::p()->data['comment']);
            unset(Session::p()->data['order_id']);
            unset(Session::p()->data['coupon']);
            unset(Session::p()->data['reward']);
            unset(Session::p()->data['gift_card']);
            unset(Session::p()->data['gift_cards']);
            
            Theme::trigger('front_customer_logout', array('customer_id' => $customer_id));
        }
        
        $data = Theme::language('account/logout');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        if (Customer::isLogged()):
            Breadcrumb::add('lang_text_account', 'account/dashboard', null, true, 'SSL');
        endif;
        
        Breadcrumb::add('lang_text_logout', 'account/logout', null, true, 'SSL');

        if (Theme::getstyle() == 'content'):
            $route = 'content/home';
        else:
            $route = 'shop/home';
        endif;
        
        $data['continue']     = Url::link($route);
        $data['text_message'] = Lang::get('lang_text_message');
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('common/success', $data));
    }
}
