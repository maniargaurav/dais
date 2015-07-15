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

class Success extends Controller {
    public function index() {
        $data = $this->theme->language('account/success');
        $this->theme->setTitle($this->language->get('lang_heading_title'));
        $this->theme->model('account/customer_group');
        
        $this->breadcrumb->add('lang_text_account', 'account/dashboard', null, true, 'SSL');
        $this->breadcrumb->add('lang_text_success', 'account/success', null, true, 'SSL');
        
        $customer_group = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());
        
        if ($customer_group && !$customer_group['approval']) {
            $data['text_message'] = sprintf($this->language->get('lang_text_message'), $this->url->link('content/contact'));
        } else {
            $data['text_message'] = sprintf($this->language->get('lang_text_approval'), $this->config->get('config_name'), $this->url->link('content/contact'));
        }
        
        if ($this->cart->hasProducts()) {
            $data['continue'] = $this->url->link('checkout/cart', '', 'SSL');
        } else {
            $data['continue'] = $this->url->link('account/dashboard', '', 'SSL');
        }
        
        $data = $this->theme->listen(__CLASS__, __FUNCTION__, $data);
        
        $this->theme->setController('header', 'shop/header');
        $this->theme->setController('footer', 'shop/footer');
        
        $data = $this->theme->renderControllers($data);
        
        $this->response->setOutput($this->theme->view('common/success', $data));
    }
}
