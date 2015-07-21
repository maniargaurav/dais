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

namespace App\Controllers\Front\Catalog;

use App\Controllers\Controller;

class Special extends Controller {
    
    public function index() {
        $data = Theme::language('catalog/special');
        
        Theme::model('catalog/product');
        Theme::model('tool/image');
        
        JS::register('storage.min', 'jquery.min');
        
        if (isset(Request::p()->get['sort'])) {
            $sort = Request::p()->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }
        
        if (isset(Request::p()->get['order'])) {
            $order = Request::p()->get['order'];
        } else {
            $order = 'asc';
        }
        
        if (isset(Request::p()->get['page'])) {
            $page = Request::p()->get['page'];
        } else {
            $page = 1;
        }
        
        if (isset(Request::p()->get['limit'])) {
            $limit = Request::p()->get['limit'];
        } else {
            $limit = Config::get('config_catalog_limit');
        }
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        $url = '';
        
        if (isset(Request::p()->get['sort'])) {
            $url.= '&sort=' . Request::p()->get['sort'];
        }
        
        if (isset(Request::p()->get['order'])) {
            $url.= '&order=' . Request::p()->get['order'];
        }
        
        if (isset(Request::p()->get['page'])) {
            $url.= '&page=' . Request::p()->get['page'];
        }
        
        if (isset(Request::p()->get['limit'])) {
            $url.= '&limit=' . Request::p()->get['limit'];
        }
        
        Breadcrumb::add('lang_heading_title', 'catalog/special', $url);
        
        $data['text_compare'] = sprintf(Lang::get('lang_text_compare'), (isset(Session::p()->data['compare']) ? count(Session::p()->data['compare']) : 0));
        
        $data['compare'] = Url::link('catalog/compare');
        
        $data['image_width'] = Config::get('config_image_product_width');
        
        $data['products'] = array();
        
        $filter = array('sort' => $sort, 'order' => $order, 'start' => ($page - 1) * $limit, 'limit' => $limit);
        
        $product_total = CatalogProduct::getTotalProductSpecials($filter);
        
        $results = CatalogProduct::getProductSpecials($filter);
        
        foreach ($results as $result) {
            if ($result['image']) {
                $image = ToolImage::resize($result['image'], Config::get('config_image_product_width'), Config::get('config_image_product_height'));
            } else {
                $image = false;
            }
            
            if ((Config::get('config_customer_price') && Customer::isLogged()) || !Config::get('config_customer_price')) {
                $price = Currency::format(Tax::calculate($result['price'], $result['tax_class_id'], Config::get('config_tax')));
            } else {
                $price = false;
            }
            
            if ((float)$result['special']) {
                $special = Currency::format(Tax::calculate($result['special'], $result['tax_class_id'], Config::get('config_tax')));
            } else {
                $special = false;
            }
            
            if (Config::get('config_tax')) {
                $tax = Currency::format((float)$result['special'] ? $result['special'] : $result['price']);
            } else {
                $tax = false;
            }
            
            if (Config::get('config_review_status')) {
                $rating = (int)$result['rating'];
            } else {
                $rating = false;
            }
            
            $data['products'][] = array(
                'product_id'  => $result['product_id'], 
                'event_id'    => $result['event_id'], 
                'thumb'       => $image, 
                'name'        => $result['name'], 
                'description' => Encode::substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..', 
                'price'       => $price, 
                'special'     => $special, 
                'tax'         => $tax, 
                'rating'      => $rating, 
                'reviews'     => sprintf(Lang::get('lang_text_reviews'), (int)$result['reviews']), 
                'href'        => Url::link('catalog/product', 'path=' . $result['paths'] . '&product_id=' . $result['product_id'] . $url)
            );
        }
        
        $url = '';
        
        if (isset(Request::p()->get['limit'])) {
            $url.= '&limit=' . Request::p()->get['limit'];
        }
        
        $data['sorts'] = array();
        
        $data['sorts'][] = array('text' => Lang::get('lang_text_default'), 'value' => 'p.sort_order-asc', 'href' => Url::link('catalog/special', 'sort=p.sort_order&order=asc' . $url));
        
        $data['sorts'][] = array('text' => Lang::get('lang_text_name_asc'), 'value' => 'pd.name-asc', 'href' => Url::link('catalog/special', 'sort=pd.name&order=asc' . $url));
        
        $data['sorts'][] = array('text' => Lang::get('lang_text_name_desc'), 'value' => 'pd.name-desc', 'href' => Url::link('catalog/special', 'sort=pd.name&order=desc' . $url));
        
        $data['sorts'][] = array('text' => Lang::get('lang_text_price_asc'), 'value' => 'ps.price-asc', 'href' => Url::link('catalog/special', 'sort=ps.price&order=asc' . $url));
        
        $data['sorts'][] = array('text' => Lang::get('lang_text_price_desc'), 'value' => 'ps.price-desc', 'href' => Url::link('catalog/special', 'sort=ps.price&order=desc' . $url));
        
        if (Config::get('config_review_status')) {
            $data['sorts'][] = array('text' => Lang::get('lang_text_rating_desc'), 'value' => 'rating-desc', 'href' => Url::link('catalog/special', 'sort=rating&order=desc' . $url));
            
            $data['sorts'][] = array('text' => Lang::get('lang_text_rating_asc'), 'value' => 'rating-asc', 'href' => Url::link('catalog/special', 'sort=rating&order=asc' . $url));
        }
        
        $data['sorts'][] = array('text' => Lang::get('lang_text_model_asc'), 'value' => 'p.model-asc', 'href' => Url::link('catalog/special', 'sort=p.model&order=asc' . $url));
        
        $data['sorts'][] = array('text' => Lang::get('lang_text_model_desc'), 'value' => 'p.model-desc', 'href' => Url::link('catalog/special', 'sort=p.model&order=desc' . $url));
        
        $url = '';
        
        if (isset(Request::p()->get['sort'])) {
            $url.= '&sort=' . Request::p()->get['sort'];
        }
        
        if (isset(Request::p()->get['order'])) {
            $url.= '&order=' . Request::p()->get['order'];
        }
        
        $data['limits'] = array();
        
        $limits = array_unique(array(Config::get('config_catalog_limit'), 32, 64, 88, 112));
        
        sort($limits);
        
        foreach ($limits as $value) {
            $data['limits'][] = array('text' => $value, 'value' => $value, 'href' => Url::link('catalog/special', $url . '&limit=' . $value));
        }
        
        $url = '';
        
        if (isset(Request::p()->get['sort'])) {
            $url.= '&sort=' . Request::p()->get['sort'];
        }
        
        if (isset(Request::p()->get['order'])) {
            $url.= '&order=' . Request::p()->get['order'];
        }
        
        if (isset(Request::p()->get['limit'])) {
            $url.= '&limit=' . Request::p()->get['limit'];
        }
        
        $data['pagination'] = Theme::paginate($product_total, $page, $limit, Lang::get('lang_text_pagination'), Url::link('catalog/special', $url . '&page={page}'));
        
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;
        
        $cookie = 'list';
        
        if (isset(Request::p()->cookie['display'])):
            $cookie = Request::p()->cookie['display'];
        endif;
        
        $data['display'] = $cookie;
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        Theme::setController('header', 'shop/header');
        Theme::setController('footer', 'shop/footer');
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('catalog/special', $data));
    }
}
