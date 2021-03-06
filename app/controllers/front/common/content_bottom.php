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

namespace App\Controllers\Front\Common;

use App\Controllers\Controller;

class ContentBottom extends Controller {
    
    public function index() {
        Theme::model('design/layout');
        Theme::model('catalog/category');
        Theme::model('catalog/product');
        Theme::model('content/page');
        
        if (isset(Request::p()->get['route'])) {
            $route = (string)Request::p()->get['route'];
        } else {
            $route = Theme::getstyle() . '/home';
        }
        
        $layout_id = 0;
        
        if ($route == 'catalog/category' && isset(Request::p()->get['path'])) {
            $path = explode('_', (string)Request::p()->get['path']);
            
            $layout_id = CatalogCategory::getCategoryLayoutId(end($path));
        }
        
        if ($route == 'catalog/product' && isset(Request::p()->get['product_id'])) {
            $layout_id = CatalogProduct::getProductLayoutId(Request::p()->get['product_id']);
        }
        
        if ($route == 'content/page' && isset(Request::p()->get['page_id'])) {
            $layout_id = ContentPage::getPageLayoutId(Request::p()->get['page_id']);
        }
        
        if (!$layout_id) {
            $layout_id = DesignLayout::getLayout($route);
        }
        
        if (!$layout_id) {
            $layout_id = Config::get('config_layout_id');
        }
        
        $widget_data = array();
        
        Theme::model('setting/module');
        
        $modules = SettingModule::getModules('widget');
        
        foreach ($modules as $module) {
            $widgets = Config::get($module['code'] . '_widget');
            
            if ($widgets) {
                foreach ($widgets as $widget) {
                    if ($widget['layout_id'] == $layout_id && $widget['position'] == 'content_bottom' && $widget['status']) {
                        $widget_data[] = array('code' => $module['code'], 'setting' => $widget, 'sort_order' => $widget['sort_order']);
                    }
                }
            }
        }
        
        $sort_order = array();
        
        $widget_count = 0;
        
        foreach ($widget_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
            $widget_count++;
        }
        
        array_multisort($sort_order, SORT_ASC, $widget_data);
        
        $data['widgets'] = array();
        
        foreach ($widget_data as $widget) {
            $widget = Theme::controller('widget/' . $widget['code'], $widget['setting']);
            
            if ($widget) {
                $data['widgets'][] = $widget;
            }
        }
        
        // Max count for blocks should be 4, anything more would be badly formatted
        $data['class'] = 0;
        
        switch ($widget_count):
        case 1:
            $count = 12;
            break;

        case 2:
            $count = 6;
            break;

        case 3:
            $count = 4;
            break;

        case 4:
            $count = 3;
            break;

        case $widget_count > 4:
            $count = 3;
            break;
        endswitch;
        
        $data['class'] = $count;
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        return View::make('common/content_bottom', $data);
    }
}
