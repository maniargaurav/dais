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

class SaleOrder extends Controller {
    
    public function index() {
        $data = Theme::language('report/sale_order');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        if (isset(Request::p()->get['filter_date_start'])) {
            $filter_date_start = Request::p()->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
        }
        
        if (isset(Request::p()->get['filter_date_end'])) {
            $filter_date_end = Request::p()->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }
        
        if (isset(Request::p()->get['filter_group'])) {
            $filter_group = Request::p()->get['filter_group'];
        } else {
            $filter_group = 'week';
        }
        
        if (isset(Request::p()->get['filter_order_status_id'])) {
            $filter_order_status_id = Request::p()->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
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
        
        if (isset(Request::p()->get['filter_group'])) {
            $url.= '&filter_group=' . Request::p()->get['filter_group'];
        }
        
        if (isset(Request::p()->get['filter_order_status_id'])) {
            $url.= '&filter_order_status_id=' . Request::p()->get['filter_order_status_id'];
        }
        
        if (isset(Request::p()->get['page'])) {
            $url.= '&page=' . Request::p()->get['page'];
        }
        
        Breadcrumb::add('lang_heading_title', 'report/sale_order', $url);
        
        Theme::model('report/sale');
        
        $data['orders'] = array();
        
        $filter = array('filter_date_start' => $filter_date_start, 'filter_date_end' => $filter_date_end, 'filter_group' => $filter_group, 'filter_order_status_id' => $filter_order_status_id, 'start' => ($page - 1) * Config::get('config_admin_limit'), 'limit' => Config::get('config_admin_limit'));
        
        $order_total = ReportSale::getTotalOrders($filter);
        
        $results = ReportSale::getOrders($filter);
        
        foreach ($results as $result) {
            $data['orders'][] = array('date_start' => date(Lang::get('lang_date_format_short'), strtotime($result['date_start'])), 'date_end' => date(Lang::get('lang_date_format_short'), strtotime($result['date_end'])), 'orders' => $result['orders'], 'products' => $result['products'], 'tax' => Currency::format($result['tax'], Config::get('config_currency')), 'total' => Currency::format($result['total'], Config::get('config_currency')));
        }
        
        Theme::model('locale/order_status');
        
        $data['order_statuses'] = LocaleOrderStatus::getOrderStatuses();
        
        $data['groups'] = array();
        
        $data['groups'][] = array('text' => Lang::get('lang_text_year'), 'value' => 'year',);
        
        $data['groups'][] = array('text' => Lang::get('lang_text_month'), 'value' => 'month',);
        
        $data['groups'][] = array('text' => Lang::get('lang_text_week'), 'value' => 'week',);
        
        $data['groups'][] = array('text' => Lang::get('lang_text_day'), 'value' => 'day',);
        
        $url = '';
        
        if (isset(Request::p()->get['filter_date_start'])) {
            $url.= '&filter_date_start=' . Request::p()->get['filter_date_start'];
        }
        
        if (isset(Request::p()->get['filter_date_end'])) {
            $url.= '&filter_date_end=' . Request::p()->get['filter_date_end'];
        }
        
        if (isset(Request::p()->get['filter_group'])) {
            $url.= '&filter_group=' . Request::p()->get['filter_group'];
        }
        
        if (isset(Request::p()->get['filter_order_status_id'])) {
            $url.= '&filter_order_status_id=' . Request::p()->get['filter_order_status_id'];
        }
        
        $data['pagination'] = Theme::paginate($order_total, $page, Config::get('config_admin_limit'), Lang::get('lang_text_pagination'), Url::link('report/sale_order', $url . '&page={page}', 'SSL'));
        
        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_group'] = $filter_group;
        $data['filter_order_status_id'] = $filter_order_status_id;
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('report/sale_order', $data));
    }
}
