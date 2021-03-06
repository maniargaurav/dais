<?php

/*
|--------------------------------------------------------------------------
|   Dais
|--------------------------------------------------------------------------
|
|   This file is part of the Dais Framework package.
|   
|   (c) Vince Kronlein <vince@dais.io>
|   
|   For the full copyright and license information, please view the LICENSE
|   file that was distributed with this source code.
|   
*/

namespace App\Controllers\Admin\Common;

use App\Controllers\Controller;

class Dashboard extends Controller {
    
    private $error;
    private $errors = array(
        'install',
        'image',
        'image_cache',
        'cache',
        'download',
        'logs'
    );
    
    public function index() {
        $data = Theme::language('common/dashboard');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        JS::register('flot.min', 'summernote.min')
            ->register('flot.resize.min', 'flot.min');
        
        if (strtotime('-24 hours', time()) > User::getLastAccess()):
            $this->checkFolders();
        endif;
        
        foreach ($this->errors as $error):
            if (isset($this->error[$error])):
                $data["error_{$error}"] = $this->error[$error];
            else:
                $data["error_{$error}"] = '';
            endif;
        endforeach;
        
        $data['token'] = Session::get('token');
        
        if (isset(Session::p()->data['success'])):
            $data['success'] = Session::p()->data['success'];
            unset(Session::p()->data['success']);
        else:
            $data['success'] = '';
        endif;
        
        if (isset(Session::p()->data['warning'])):
            $data['error_warning'] = Session::p()->data['warning'];
            unset(Session::p()->data['warning']);
        else:
            $data['error_warning'] = '';
        endif;
        
        Theme::model('sale/order');
        
        $data['total_sale']      = Currency::format(SaleOrder::getTotalSales() , Config::get('config_currency'));
        $data['total_sale_year'] = Currency::format(SaleOrder::getTotalSalesByYear(date('Y')) , Config::get('config_currency'));
        $data['total_order']     = SaleOrder::getTotalOrders();
        
        Theme::model('people/customer');
        
        $data['total_customer']          = PeopleCustomer::getTotalCustomers();
        $data['total_customer_approval'] = PeopleCustomer::getTotalCustomersAwaitingApproval();
        
        Theme::model('catalog/review');
        
        $data['total_review']          = CatalogReview::getTotalReviews();
        $data['total_review_approval'] = CatalogReview::getTotalReviewsAwaitingApproval();
        
        $data['orders'] = array();
        
        $filter = array(
            'sort'  => 'o.date_added',
            'order' => 'desc',
            'start' => 0,
            'limit' => 10
        );
        
        $results = SaleOrder::getOrders($filter);
        
        foreach ($results as $result) {
            $action = array();
            
            $action[] = array(
                'text' => Lang::get('lang_text_view') ,
                'href' => Url::link('sale/order/info', 'order_id=' . $result['order_id'], 'SSL')
            );
            
            $data['orders'][] = array(
                'order_id'   => $result['order_id'],
                'customer'   => $result['customer'],
                'status'     => $result['status'],
                'date_added' => date(Lang::get('lang_date_format_short') , strtotime($result['date_added'])) ,
                'total'      => Currency::format($result['total'], $result['currency_code'], $result['currency_value']) ,
                'action'     => $action
            );
        }
        
        if (Config::get('config_currency_auto')) {
            Theme::model('locale/currency');
            
            LocaleCurrency::updateCurrencies();
        }
        
        Theme::loadjs('javascript/common/dashboard', $data);
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('common/dashboard', $data));
    }
    
    public function sale() {
        Theme::language('common/dashboard');
        
        $json = array();
        
        Theme::model('report/dashboard');
        
        $json['orders']            = array();
        $json['customers']         = array();
        $json['xaxis']             = array();
        
        $json['order']['label']    = Lang::get('lang_text_order');
        $json['customer']['label'] = Lang::get('lang_text_customer');
        
        if (isset(Request::p()->get['range'])) {
            $range = Request::p()->get['range'];
        } else {
            $range = 'day';
        }
        
        switch ($range) {
            default:
            case 'day':
                $results = ReportDashboard::getTotalOrdersByDay();
                
                foreach ($results as $key => $value) {
                    $json['order']['data'][] = array($key, $value['total']);
                }
                
                $results = ReportDashboard::getTotalCustomersByDay();
                
                foreach ($results as $key => $value) {
                    $json['customer']['data'][] = array($key,$value['total']);
                }
                
                for ($i = 0; $i < 24; $i++) {
                    $json['xaxis'][] = array($i, $i);
                }
                break;

            case 'week':
                $results = ReportDashboard::getTotalOrdersByWeek();
                
                foreach ($results as $key => $value) {
                    $json['order']['data'][] = array($key, $value['total']);
                }
                
                $results = ReportDashboard::getTotalCustomersByWeek();
                
                foreach ($results as $key => $value) {
                    $json['customer']['data'][] = array($key, $value['total']);
                }
                
                $date_start = strtotime('-' . date('w') . ' days');
                
                for ($i = 0; $i < 7; $i++) {
                    $date = date('Y-m-d', $date_start + ($i * 86400));
                    
                    $json['xaxis'][] = array(date('w', strtotime($date)), date('D', strtotime($date)));
                }
                break;

            case 'month':
                $results = ReportDashboard::getTotalOrdersByMonth();
                
                foreach ($results as $key => $value) {
                    $json['order']['data'][] = array($key, $value['total']);
                }
                
                $results = ReportDashboard::getTotalCustomersByMonth();
                
                foreach ($results as $key => $value) {
                    $json['customer']['data'][] = array($key, $value['total']);
                }
                
                $date_t = date('t');
                for ($i = 1; $i <= $date_t; $i++) {
                    $date = date('Y') . '-' . date('m') . '-' . $i;
                    
                    $json['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));
                }
                break;

            case 'year':
                $results = ReportDashboard::getTotalOrdersByYear();
                
                foreach ($results as $key => $value) {
                    $json['order']['data'][] = array($key, $value['total']);
                }
                
                $results = ReportDashboard::getTotalCustomersByYear();
                
                foreach ($results as $key => $value) {
                    $json['customer']['data'][] = array($key, $value['total']);
                }
                
                for ($i = 1; $i <= 12; $i++) {
                    $json['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
                }
                break;
        }
        
        $json = Theme::listen(__CLASS__, __FUNCTION__, $json);
        
        Response::setOutput(json_encode($json));
    }
    
    public function login() {
        $route = '';
        
        if (isset(Request::p()->get['route'])) {
            $part = explode('/', Request::p()->get['route']);
            
            if (isset($part[0])) {
                $route.= $part[0];
            }
            
            if (isset($part[1])) {
                $route.= '/' . $part[1];
            }
        }
        
        $ignore = array(
            'common/login',
            'common/forgotten',
            'common/reset',
            'common/javascript',
            'common/css'
        );
        
        if (!User::isLogged() && !in_array($route, $ignore)) {
            return new Action('common/login');
        }
        
        if (isset(Request::p()->get['route'])) {
            $ignore = array(
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission',
                'common/breadcrumb',
                'common/menu',
                'common/javascript',
                'common/css'
            );
            
            $config_ignore = array();
            
            $ignore = array_merge($ignore, $config_ignore);
            
            if (!in_array($route, $ignore) && !Response::match()) {
                return new Action('common/login');
            }
        } else {
            if (!Response::match()) {
                return new Action('common/login');
            }
        }
    }
    
    public function permission() {
        if (isset(Request::p()->get['route'])) {
            $route = '';
            
            $part = explode('/', Request::p()->get['route']);
            
            if (isset($part[0])) {
                $route.= $part[0];
            }
            
            if (isset($part[1])) {
                $route.= '/' . $part[1];
            }
            
            $ignore = array(
                'common/dashboard',
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'common/breadcrumb',
                'common/menu',
                'error/not_found',
                'error/permission',
                'common/javascript',
                'common/css'
            );
            
            if (!in_array($route, $ignore) && !User::hasPermission('access', $route)) {
                return new Action('error/permission');
            }
        }
    }
    
    public function checkFolders() {
        Theme::language('common/dashboard');
        
        // Check image directory is writable
        $file = Config::get('path.image') . 'test';
        $handle = fopen($file, 'a+');
        
        fwrite($handle, '');
        fclose($handle);
        
        if (!is_readable($file)):
            $this->error['image'] = sprintf(Lang::get('lang_error_image') , Config::get('path.image'));
        else:
            $this->error['image'] = '';
            unlink($file);
        endif;
        
        // Check image cache directory is writable
        $file = Config::get('path.image') . 'cache/test';
        $handle = fopen($file, 'a+');
        
        fwrite($handle, '');
        fclose($handle);
        
        if (!is_readable($file)):
            $this->error['image_cache'] = sprintf(Lang::get('lang_error_image_cache') , Config::get('path.image') . 'cache/');
        else:
            $this->error['image_cache'] = '';
            unlink($file);
        endif;
        
        // Check cache directory is writable
        $file = Config::get('path.cache') . 'test';
        $handle = fopen($file, 'a+');
        
        fwrite($handle, '');
        fclose($handle);
        
        if (!is_readable($file)):
            $this->error['cache'] = sprintf(Lang::get('lang_error_image_cache') , Config::get('path.cache'));
        else:
            $this->error['cache'] = '';
            unlink($file);
        endif;
        
        // Check download directory is writable
        $file = Config::get('path.download') . 'test';
        $handle = fopen($file, 'a+');
        
        fwrite($handle, '');
        fclose($handle);
        
        if (!is_readable($file)):
            $this->error['download'] = sprintf(Lang::get('lang_error_download') , Config::get('path.download'));
        else:
            $this->error['download'] = '';
            unlink($file);
        endif;
        
        // Check logs directory is writable
        $file = Config::get('path.logs') . 'test';
        $handle = fopen($file, 'a+');
        
        fwrite($handle, '');
        fclose($handle);
        
        if (!is_readable($file)):
            $this->error['logs'] = sprintf(Lang::get('lang_error_logs') , Config::get('path.logs'));
        else:
            $this->error['logs'] = '';
            unlink($file);
        endif;
        
        if (!$this->error):
            Theme::model('report/dashboard');
            ReportDashboard::setLastAccess(User::getId());
            Session::p()->data['user_last_access'] = time();
        endif;
        
        return !$this->error;
    }
}
