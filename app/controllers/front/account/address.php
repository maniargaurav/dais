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

class Address extends Controller {
    
    private $error = array();
    
    public function index() {
        if (!Customer::isLogged()) {
            Session::p()->data['redirect'] = Url::link('account/address', '', 'SSL');
            
            Response::redirect(Url::link('account/login', '', 'SSL'));
        }
        
        Theme::language('account/address');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('account/address');
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getList();
    }
    
    public function insert() {
        if (!Customer::isLogged()) {
            Session::p()->data['redirect'] = Url::link('account/address', '', 'SSL');
            
            Response::redirect(Url::link('account/login', '', 'SSL'));
        }
        
        Theme::language('account/address');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        Theme::model('account/address');
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            AccountAddress::addAddress(Request::post());
            
            Session::p()->data['success'] = Lang::get('lang_text_insert');
            
            Response::redirect(Url::link('account/address', '', 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getForm();
    }
    
    public function update() {
        if (!Customer::isLogged()) {
            Session::p()->data['redirect'] = Url::link('account/address', '', 'SSL');
            
            Response::redirect(Url::link('account/login', '', 'SSL'));
        }
        
        Theme::language('account/address');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        Theme::model('account/address');
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            AccountAddress::editAddress(Request::p()->get['address_id'], Request::post());
            
            // Default Shipping Address
            if (isset(Session::p()->data['shipping_address_id']) && (Request::p()->get['address_id'] == Session::p()->data['shipping_address_id'])) {
                Session::p()->data['shipping_country_id'] = Request::p()->post['country_id'];
                Session::p()->data['shipping_zone_id'] = Request::p()->post['zone_id'];
                Session::p()->data['shipping_postcode'] = Request::p()->post['postcode'];
                
                unset(Session::p()->data['shipping_method']);
                unset(Session::p()->data['shipping_methods']);
            }
            
            // Default Payment Address
            if (isset(Session::p()->data['payment_address_id']) && (Request::p()->get['address_id'] == Session::p()->data['payment_address_id'])) {
                Session::p()->data['payment_country_id'] = Request::p()->post['country_id'];
                Session::p()->data['payment_zone_id'] = Request::p()->post['zone_id'];
                
                unset(Session::p()->data['payment_method']);
                unset(Session::p()->data['payment_methods']);
            }
            
            Session::p()->data['success'] = Lang::get('lang_text_update');
            
            Response::redirect(Url::link('account/address', '', 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getForm();
    }
    
    public function delete() {
        if (!Customer::isLogged()) {
            Session::p()->data['redirect'] = Url::link('account/address', '', 'SSL');
            
            Response::redirect(Url::link('account/login', '', 'SSL'));
        }
        
        Theme::language('account/address');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        Theme::model('account/address');
        
        if (isset(Request::p()->get['address_id']) && $this->validateDelete()) {
            AccountAddress::deleteAddress(Request::p()->get['address_id']);
            
            // Default Shipping Address
            if (isset(Session::p()->data['shipping_address_id']) && (Request::p()->get['address_id'] == Session::p()->data['shipping_address_id'])) {
                unset(Session::p()->data['shipping_address_id']);
                unset(Session::p()->data['shipping_country_id']);
                unset(Session::p()->data['shipping_zone_id']);
                unset(Session::p()->data['shipping_postcode']);
                unset(Session::p()->data['shipping_method']);
                unset(Session::p()->data['shipping_methods']);
            }
            
            // Default Payment Address
            if (isset(Session::p()->data['payment_address_id']) && (Request::p()->get['address_id'] == Session::p()->data['payment_address_id'])) {
                unset(Session::p()->data['payment_address_id']);
                unset(Session::p()->data['payment_country_id']);
                unset(Session::p()->data['payment_zone_id']);
                unset(Session::p()->data['payment_method']);
                unset(Session::p()->data['payment_methods']);
            }
            
            Session::p()->data['success'] = Lang::get('lang_text_delete');
            
            Response::redirect(Url::link('account/address', '', 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getList();
    }
    
    protected function getList() {
        $data = Theme::language('account/address');
        
        Breadcrumb::add('lang_text_account', 'account/dashboard', null, true, 'SSL');
        Breadcrumb::add('lang_heading_title', 'account/address', null, true, 'SSL');
        
        if (isset($this->error['warning'])) {
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
        
        $data['addresses'] = array();
        
        $results = AccountAddress::getAddresses();
        
        foreach ($results as $result) {
            if ($result['address_format']) {
                $format = $result['address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }
            
            $find = array(
                '{firstname}', 
                '{lastname}', 
                '{company}', 
                '{address_1}', 
                '{address_2}', 
                '{city}', 
                '{postcode}', 
                '{zone}', 
                '{zone_code}', 
                '{country}'
            );
            
            $replace = array(
                'firstname' => $result['firstname'], 
                'lastname'  => $result['lastname'], 
                'company'   => $result['company'], 
                'address_1' => $result['address_1'], 
                'address_2' => $result['address_2'], 
                'city'      => $result['city'], 
                'postcode'  => $result['postcode'], 
                'zone'      => $result['zone'], 
                'zone_code' => $result['zone_code'], 
                'country'   => $result['country']
            );
            
            $data['addresses'][] = array(
                'address_id' => $result['address_id'], 
                'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))), 
                'update'     => Url::link('account/address/update', 'address_id=' . $result['address_id'], 'SSL'), 
                'delete'     => Url::link('account/address/delete', 'address_id=' . $result['address_id'], 'SSL')
            );
        }
        
        $data['insert'] = Url::link('account/address/insert', '', 'SSL');
        $data['back']   = Url::link('account/dashboard', '', 'SSL');
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        Theme::setController('header', 'shop/header');
        Theme::setController('footer', 'shop/footer');
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('account/address_list', $data));
    }
    
    protected function getForm() {
        $data = Theme::language('account/address');
        
        Breadcrumb::add('lang_text_account', 'account/dashboard', null, true, 'SSL');
        Breadcrumb::add('lang_heading_title', 'account/address', null, true, 'SSL');
        
        if (!isset(Request::p()->get['address_id'])) {
            Breadcrumb::add('lang_text_edit_address', 'account/address/edit', null, true, 'SSL');
        } else {
            Breadcrumb::add('lang_text_edit_address', 'account/address/edit', 'address_id=' . Request::p()->get['address_id'], true, 'SSL');
        }
        
        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }
        
        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }
        
        if (isset($this->error['company_id'])) {
            $data['error_company_id'] = $this->error['company_id'];
        } else {
            $data['error_company_id'] = '';
        }
        
        if (isset($this->error['tax_id'])) {
            $data['error_tax_id'] = $this->error['tax_id'];
        } else {
            $data['error_tax_id'] = '';
        }
        
        if (isset($this->error['address_1'])) {
            $data['error_address_1'] = $this->error['address_1'];
        } else {
            $data['error_address_1'] = '';
        }
        
        if (isset($this->error['city'])) {
            $data['error_city'] = $this->error['city'];
        } else {
            $data['error_city'] = '';
        }
        
        if (isset($this->error['postcode'])) {
            $data['error_postcode'] = $this->error['postcode'];
        } else {
            $data['error_postcode'] = '';
        }
        
        if (isset($this->error['country'])) {
            $data['error_country'] = $this->error['country'];
        } else {
            $data['error_country'] = '';
        }
        
        if (isset($this->error['zone'])) {
            $data['error_zone'] = $this->error['zone'];
        } else {
            $data['error_zone'] = '';
        }
        
        if (!isset(Request::p()->get['address_id'])) {
            $data['action'] = Url::link('account/address/insert', '', 'SSL');
        } else {
            $data['action'] = Url::link('account/address/update', 'address_id=' . Request::p()->get['address_id'], 'SSL');
        }
        
        if (isset(Request::p()->get['address_id']) && (Request::p()->server['REQUEST_METHOD'] != 'POST')) {
            $address_info = AccountAddress::getAddress(Request::p()->get['address_id']);
        }
        
        if (isset(Request::p()->post['firstname'])) {
            $data['firstname'] = Request::p()->post['firstname'];
        } elseif (!empty($address_info)) {
            $data['firstname'] = $address_info['firstname'];
        } else {
            $data['firstname'] = '';
        }
        
        if (isset(Request::p()->post['lastname'])) {
            $data['lastname'] = Request::p()->post['lastname'];
        } elseif (!empty($address_info)) {
            $data['lastname'] = $address_info['lastname'];
        } else {
            $data['lastname'] = '';
        }
        
        if (isset(Request::p()->post['company'])) {
            $data['company'] = Request::p()->post['company'];
        } elseif (!empty($address_info)) {
            $data['company'] = $address_info['company'];
        } else {
            $data['company'] = '';
        }
        
        if (isset(Request::p()->post['company_id'])) {
            $data['company_id'] = Request::p()->post['company_id'];
        } elseif (!empty($address_info)) {
            $data['company_id'] = $address_info['company_id'];
        } else {
            $data['company_id'] = '';
        }
        
        if (isset(Request::p()->post['tax_id'])) {
            $data['tax_id'] = Request::p()->post['tax_id'];
        } elseif (!empty($address_info)) {
            $data['tax_id'] = $address_info['tax_id'];
        } else {
            $data['tax_id'] = '';
        }
        
        Theme::model('account/customer_group');
        
        $customer_group_info = AccountCustomerGroup::getCustomerGroup(Customer::getGroupId());
        
        if ($customer_group_info) {
            $data['company_id_display'] = $customer_group_info['company_id_display'];
        } else {
            $data['company_id_display'] = '';
        }
        
        if ($customer_group_info) {
            $data['tax_id_display'] = $customer_group_info['tax_id_display'];
        } else {
            $data['tax_id_display'] = '';
        }
        
        if (isset(Request::p()->post['address_1'])) {
            $data['address_1'] = Request::p()->post['address_1'];
        } elseif (!empty($address_info)) {
            $data['address_1'] = $address_info['address_1'];
        } else {
            $data['address_1'] = '';
        }
        
        if (isset(Request::p()->post['address_2'])) {
            $data['address_2'] = Request::p()->post['address_2'];
        } elseif (!empty($address_info)) {
            $data['address_2'] = $address_info['address_2'];
        } else {
            $data['address_2'] = '';
        }
        
        if (isset(Request::p()->post['postcode'])) {
            $data['postcode'] = Request::p()->post['postcode'];
        } elseif (!empty($address_info)) {
            $data['postcode'] = $address_info['postcode'];
        } else {
            $data['postcode'] = '';
        }
        
        if (isset(Request::p()->post['city'])) {
            $data['city'] = Request::p()->post['city'];
        } elseif (!empty($address_info)) {
            $data['city'] = $address_info['city'];
        } else {
            $data['city'] = '';
        }
        
        if (isset(Request::p()->post['country_id'])) {
            $data['country_id'] = Request::p()->post['country_id'];
        } elseif (!empty($address_info)) {
            $data['country_id'] = $address_info['country_id'];
        } else {
            $data['country_id'] = Config::get('config_country_id');
        }
        
        if (isset(Request::p()->post['zone_id'])) {
            $data['zone_id'] = Request::p()->post['zone_id'];
        } elseif (!empty($address_info)) {
            $data['zone_id'] = $address_info['zone_id'];
        } else {
            $data['zone_id'] = '';
        }
        
        $data['params'] = htmlentities('{"zone_id":"' . $data['zone_id'] . '","select":"' . Lang::get('lang_text_select') . '","none":"' . Lang::get('lang_text_none') . '"}');
        
        Theme::model('locale/country');
        
        $data['countries'] = LocaleCountry::getCountries();
        
        if (isset(Request::p()->post['default'])) {
            $data['default'] = Request::p()->post['default'];
        } elseif (isset(Request::p()->get['address_id'])) {
            $data['default'] = Customer::getAddressId() == Request::p()->get['address_id'];
        } else {
            $data['default'] = false;
        }
        
        $data['back'] = Url::link('account/address', '', 'SSL');
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        Theme::setController('header', 'shop/header');
        Theme::setController('footer', 'shop/footer');
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('account/address_form', $data));
    }
    
    protected function validateForm() {
        if ((Encode::strlen(Request::p()->post['firstname']) < 1) || (Encode::strlen(Request::p()->post['firstname']) > 32)) {
            $this->error['firstname'] = Lang::get('lang_error_firstname');
        }
        
        if ((Encode::strlen(Request::p()->post['lastname']) < 1) || (Encode::strlen(Request::p()->post['lastname']) > 32)) {
            $this->error['lastname'] = Lang::get('lang_error_lastname');
        }
        
        if ((Encode::strlen(Request::p()->post['address_1']) < 3) || (Encode::strlen(Request::p()->post['address_1']) > 128)) {
            $this->error['address_1'] = Lang::get('lang_error_address_1');
        }
        
        if ((Encode::strlen(Request::p()->post['city']) < 2) || (Encode::strlen(Request::p()->post['city']) > 128)) {
            $this->error['city'] = Lang::get('lang_error_city');
        }
        
        Theme::model('locale/country');
        
        $country_info = LocaleCountry::getCountry(Request::p()->post['country_id']);
        
        if ($country_info) {
            if ($country_info['postcode_required'] && (Encode::strlen(Request::p()->post['postcode']) < 2) || (Encode::strlen(Request::p()->post['postcode']) > 10)) {
                $this->error['postcode'] = Lang::get('lang_error_postcode');
            }
            
            if (Config::get('config_vat') && !empty(Request::p()->post['tax_id']) && ($this->vat->validate($country_info['iso_code_2'], Request::p()->post['tax_id']) == 'invalid')) {
                $this->error['tax_id'] = Lang::get('lang_error_vat');
            }
        }
        
        if (Request::p()->post['country_id'] == '') {
            $this->error['country'] = Lang::get('lang_error_country');
        }
        
        if (!isset(Request::p()->post['zone_id']) || Request::p()->post['zone_id'] == '') {
            $this->error['zone'] = Lang::get('lang_error_zone');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
    
    protected function validateDelete() {
        if (AccountAddress::getTotalAddresses() == 1) {
            $this->error['warning'] = Lang::get('lang_error_delete');
        }
        
        if (Customer::getAddressId() == Request::p()->get['address_id']) {
            $this->error['warning'] = Lang::get('lang_error_default');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
    
    public function country() {
        $json = array();
        
        Theme::model('locale/country');
        
        $country_info = LocaleCountry::getCountry(Request::p()->get['country_id']);
        
        if ($country_info) {
            Theme::model('locale/zone');
            
            $json = array('country_id' => $country_info['country_id'], 'name' => $country_info['name'], 'iso_code_2' => $country_info['iso_code_2'], 'iso_code_3' => $country_info['iso_code_3'], 'address_format' => $country_info['address_format'], 'postcode_required' => $country_info['postcode_required'], 'zone' => LocaleZone::getZonesByCountryId(Request::p()->get['country_id']), 'status' => $country_info['status']);
        }
        
        $json = Theme::listen(__CLASS__, __FUNCTION__, $json);
        
        Response::setOutput(json_encode($json));
    }
}
