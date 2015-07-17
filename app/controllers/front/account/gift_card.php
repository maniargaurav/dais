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

class GiftCard extends Controller {
    
    private $error = array();
    
    public function index() {
        $data = Theme::language('account/gift_card');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        if (!isset(Session::p()->data['gift_cards'])) {
            Session::p()->data['gift_cards'] = array();
        }
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            Session::p()->data['gift_cards'][mt_rand()] = array(
                'description'       => sprintf(Lang::get('lang_text_for'), Currency::format(Currency::convert(Request::p()->post['amount'], Currency::getCode(), Config::get('config_currency'))), Request::p()->post['to_name']), 
                'to_name'           => Request::p()->post['to_name'], 
                'to_email'          => Request::p()->post['to_email'], 
                'from_name'         => Request::p()->post['from_name'], 
                'from_email'        => Request::p()->post['from_email'], 
                'gift_card_theme_id' => Request::p()->post['gift_card_theme_id'], 
                'message'           => Request::p()->post['message'], 
                'amount'            => Currency::convert(Request::p()->post['amount'], Currency::getCode(), Config::get('config_currency'))
            );
            
            Response::redirect(Url::link('account/gift_card/success'));
        }
        
        Breadcrumb::add('lang_text_account', 'account/dashboard', null, true, 'SSL');
        Breadcrumb::add('lang_text_gift_card', 'account/gift_card', null, true, 'SSL');
        
        $data['entry_amount'] = sprintf(Lang::get('lang_entry_amount'), Currency::format(Config::get('config_gift_card_min')), Currency::format(Config::get('config_gift_card_max')));
        
        $data['button_continue'] = Lang::get('lang_button_continue');
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['to_name'])) {
            $data['error_to_name'] = $this->error['to_name'];
        } else {
            $data['error_to_name'] = '';
        }
        
        if (isset($this->error['to_email'])) {
            $data['error_to_email'] = $this->error['to_email'];
        } else {
            $data['error_to_email'] = '';
        }
        
        if (isset($this->error['from_name'])) {
            $data['error_from_name'] = $this->error['from_name'];
        } else {
            $data['error_from_name'] = '';
        }
        
        if (isset($this->error['from_email'])) {
            $data['error_from_email'] = $this->error['from_email'];
        } else {
            $data['error_from_email'] = '';
        }
        
        if (isset($this->error['theme'])) {
            $data['error_theme'] = $this->error['theme'];
        } else {
            $data['error_theme'] = '';
        }
        
        if (isset($this->error['amount'])) {
            $data['error_amount'] = $this->error['amount'];
        } else {
            $data['error_amount'] = '';
        }
        
        $data['action'] = Url::link('account/gift_card', '', 'SSL');
        
        if (isset(Request::p()->post['to_name'])) {
            $data['to_name'] = Request::p()->post['to_name'];
        } else {
            $data['to_name'] = '';
        }
        
        if (isset(Request::p()->post['to_email'])) {
            $data['to_email'] = Request::p()->post['to_email'];
        } else {
            $data['to_email'] = '';
        }
        
        if (isset(Request::p()->post['from_name'])) {
            $data['from_name'] = Request::p()->post['from_name'];
        } elseif (Customer::isLogged()) {
            $data['from_name'] = Customer::getFirstName() . ' ' . Customer::getLastName();
        } else {
            $data['from_name'] = '';
        }
        
        if (isset(Request::p()->post['from_email'])) {
            $data['from_email'] = Request::p()->post['from_email'];
        } elseif (Customer::isLogged()) {
            $data['from_email'] = Customer::getEmail();
        } else {
            $data['from_email'] = '';
        }
        
        Theme::model('checkout/gift_card_theme');
        
        $data['gift_card_themes'] = CheckoutGiftCardTheme::getGiftcardThemes();
        
        if (isset(Request::p()->post['gift_card_theme_id'])) {
            $data['gift_card_theme_id'] = Request::p()->post['gift_card_theme_id'];
        } else {
            $data['gift_card_theme_id'] = '';
        }
        
        if (isset(Request::p()->post['message'])) {
            $data['message'] = Request::p()->post['message'];
        } else {
            $data['message'] = '';
        }
        
        if (isset(Request::p()->post['amount'])) {
            $data['amount'] = Request::p()->post['amount'];
        } else {
            $data['amount'] = Currency::format(25, Config::get('config_currency'), false, false);
        }
        
        if (isset(Request::p()->post['agree'])) {
            $data['agree'] = Request::p()->post['agree'];
        } else {
            $data['agree'] = false;
        }
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        Theme::setController('header', 'shop/header');
        Theme::setController('footer', 'shop/footer');
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::render('account/gift_card', $data));
    }
    
    public function success() {
        $data = Theme::language('account/gift_card');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        Breadcrumb::add('lang_heading_title', 'account/gift_card', null, true, 'SSL');
        
        $data['continue'] = Url::link('checkout/cart');
        $data['text_message'] = Lang::get('lang_text_message');
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        Theme::setController('header', 'shop/header');
        Theme::setController('footer', 'shop/footer');
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::render('common/success', $data));
    }
    
    protected function validate() {
        if ((Encode::strlen(Request::p()->post['to_name']) < 1) || (Encode::strlen(Request::p()->post['to_name']) > 64)) {
            $this->error['to_name'] = Lang::get('lang_error_to_name');
        }
        
        if ((Encode::strlen(Request::p()->post['to_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', Request::p()->post['to_email'])) {
            $this->error['to_email'] = Lang::get('lang_error_email');
        }
        
        if ((Encode::strlen(Request::p()->post['from_name']) < 1) || (Encode::strlen(Request::p()->post['from_name']) > 64)) {
            $this->error['from_name'] = Lang::get('lang_error_from_name');
        }
        
        if ((Encode::strlen(Request::p()->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', Request::p()->post['from_email'])) {
            $this->error['from_email'] = Lang::get('lang_error_email');
        }
        
        if (!isset(Request::p()->post['gift_card_theme_id'])) {
            $this->error['theme'] = Lang::get('lang_error_theme');
        }
        
        if ((Currency::convert(Request::p()->post['amount'], Currency::getCode(), Config::get('config_currency')) < Config::get('config_gift_card_min')) || (Currency::convert(Request::p()->post['amount'], Currency::getCode(), Config::get('config_currency')) > Config::get('config_gift_card_max'))) {
            $this->error['amount'] = sprintf(Lang::get('lang_error_amount'), Currency::format(Config::get('config_gift_card_min')), Currency::format(Config::get('config_gift_card_max')) . ' ' . Currency::getCode());
        }
        
        if (!isset(Request::p()->post['agree'])) {
            $this->error['warning'] = Lang::get('lang_error_agree');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
