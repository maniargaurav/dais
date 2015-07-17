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

namespace App\Controllers\Admin\Report;

use App\Controllers\Controller;

class AffiliateCommission extends Controller {
    
    public function index() {
        $data = Theme::language('report/affiliate_commission');
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        if (isset(Request::p()->get['filter_date_start'])) {
            $filter_date_start = Request::p()->get['filter_date_start'];
        } else {
            $filter_date_start = '';
        }
        
        if (isset(Request::p()->get['filter_date_end'])) {
            $filter_date_end = Request::p()->get['filter_date_end'];
        } else {
            $filter_date_end = '';
        }
        
        if (isset(Request::p()->get['page'])) {
            $page = Request::p()->get['page'];
        } else {
            $page = 1;
        }
        
        $url = '';
        
        if (isset(Request::p()->get['filter_date_start'])) {
            $url.= '&filter_date_start=' . Request::p()->get['filter_date_start'];
        }
        
        if (isset(Request::p()->get['filter_date_end'])) {
            $url.= '&filter_date_end=' . Request::p()->get['filter_date_end'];
        }
        
        if (isset(Request::p()->get['page'])) {
            $url.= '&page=' . Request::p()->get['page'];
        }
        
        Breadcrumb::add('lang_heading_title', 'report/affiliate_commission', $url);
        Theme::model('report/affiliate');
        
        $data['affiliates'] = array();
        
        $filter = array(
            'filter_date_start' => $filter_date_start, 
            'filter_date_end'   => $filter_date_end, 
            'start'             => ($page - 1) * Config::get('config_admin_limit'), 
            'limit'             => Config::get('config_admin_limit')
        );
        
        $affiliate_total = ReportAffiliate::getTotalCommission($filter);
        $results         = ReportAffiliate::getCommission($filter);
        
        foreach ($results as $result) {
            $action = array();
            
            $action[] = array(
                'text' => Lang::get('lang_text_edit'), 
                'href' => Url::link('people/customer/update', '' . 'customer_id=' . $result['customer_id'] . $url, 'SSL')
            );
            
            $data['affiliates'][] = array(
                'affiliate'  => $result['affiliate'], 
                'email'      => $result['email'], 
                'status'     => ($result['status'] ? Lang::get('lang_text_enabled') : Lang::get('lang_text_disabled')), 
                'commission' => Currency::format($result['commission'], Config::get('config_currency')), 
                'orders'     => $result['orders'], 
                'total'      => Currency::format($result['total'], Config::get('config_currency')), 
                'action'     => $action
            );
        }
        
        $url = '';
        
        if (isset(Request::p()->get['filter_date_start'])) {
            $url.= '&filter_date_start=' . Request::p()->get['filter_date_start'];
        }
        
        if (isset(Request::p()->get['filter_date_end'])) {
            $url.= '&filter_date_end=' . Request::p()->get['filter_date_end'];
        }
        
        $data['pagination'] = Theme::paginate(
            $affiliate_total, 
            $page, 
            Config::get('config_admin_limit'), 
            Lang::get('lang_text_pagination'), 
            Url::link('report/affiliate_commission', '' . $url . '&page={page}', 'SSL')
        );
        
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end']   = $filter_date_end;
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::render('report/affiliate_commission', $data));
    }
}
