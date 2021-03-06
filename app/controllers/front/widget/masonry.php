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

namespace App\Controllers\Front\Widget;

use App\Controllers\Controller;

class Masonry extends Controller {
    
    public function index($setting) {
        static $widget = 0;
        
        $data = Theme::language('widget/masonry');
        
        JS::register('masonry.min', 'bootstrap.min')->register('imagesloaded.min', 'masonry.min');
        
        $data['heading_title'] = Lang::get('lang_heading_' . $setting['product_type']);
        
        $data['text_empty'] = sprintf(Lang::get('lang_text_empty') , $setting['product_type']);
        
        Theme::model('catalog/product');
        Theme::model('tool/image');
        
        $data['button'] = $setting['button'];
        $data['span'] = $setting['span'];
        
        $data['class_row'] = ($setting['span'] == 1) ? 'slim-row' : 'row';
        
        $class_col = array(
            1 => 'slim-col-xs-4 slim-col-sm-2 slim-col-md-1',
            2 => 'col-xs-6 col-sm-3 col-md-2',
            3 => 'col-xs-12 col-sm-4 col-md-3',
            4 => 'col-xs-12 col-sm-6 col-md-4',
            6 => 'col-xs-12 col-sm-6'
        );
        
        $data['class_col'] = $class_col[$setting['span']];
        
        if (!$setting['height']) {
            $data['class_1'] = 'masonry';
            $data['class_2'] = 'thumbnail';
            $data['class_3'] = '';
        } else {
            $data['class_1'] = 'block';
            $data['class_2'] = 'spacer';
            $data['class_3'] = 'thumbnail';
        }
        
        $key = 'products.masonry.' . (int)$widget;
        $cachefile = Cache::get($key);
        
        if (is_bool($cachefile)) {
            $image_width = 60 + (($setting['span'] - 1) * 100);
            
            $masonry_products = array();
            
            if ($setting['product_type'] == 'featured') {
                $results = array();
                
                $products = explode(',', Config::get('featured_product'));
                
                if (empty($setting['limit'])) {
                    $setting['limit'] = 5;
                }
                
                $products = array_slice($products, 0, (int)$setting['limit']);
                
                foreach ($products as $product_id) {
                    $product_info = CatalogProduct::getProduct($product_id);
                    
                    if ($product_info) {
                        $results[] = $product_info;
                    }
                }
            } elseif ($setting['product_type'] == 'special') {
                $results = CatalogProduct::getProductSpecials(array(
                    'sort' => 'pd.name',
                    'order' => 'asc',
                    'start' => 0,
                    'limit' => $setting['limit']
                ));
            } elseif ($setting['product_type'] == 'best_seller') {
                $results = CatalogProduct::getBestSellerProducts($setting['limit']);
            } else {
                $results = CatalogProduct::getProducts(array(
                    'sort' => 'p.date_added',
                    'order' => 'desc',
                    'start' => 0,
                    'limit' => $setting['limit']
                ));
            }
            
            $display_price = Config::get('config_customer_price') && Customer::isLogged() || !Config::get('config_customer_price');
            
            $chars = $setting['span'] * 40;
            
            foreach ($results as $result) {
                if ($result['image'] && file_exists(Config::get('path.image') . $result['image'])) {
                    if ($setting['height']) {
                        $height = $setting['height'];
                    } else {
                        $size = getimagesize(Config::get('path.image') . $result['image']);
                        
                        $height = ceil(((int)$image_width / $size[0]) * $size[1]);
                    }
                    
                    $image = ToolImage::resize($result['image'], (int)$image_width, $height);
                    
                } else {
                    $image = '';
                }
                
                if ($setting['description'] && $result['description']) {
                    $description = $this->formatDescription($result['description'], $chars);
                } else {
                    $description = false;
                }
                
                if ($display_price && !number_format($result['price'])) {
                    $price = Lang::get('lang_text_free');
                } elseif ($display_price) {
                    $price = \Currency::format(Tax::calculate($result['price'], $result['tax_class_id'], Config::get('config_tax')));
                } else {
                    $price = false;
                }
                
                if ($display_price && (float)$result['special']) {
                    $special = \Currency::format(Tax::calculate($result['special'], $result['tax_class_id'], Config::get('config_tax')));
                } else {
                    $special = false;
                }
                
                if (Config::get('config_review_status') && $setting['span'] > 1) {
                    $rating = $result['rating'];
                } else {
                    $rating = false;
                }

                $masonry_products[] = array(
                    'product_id'  => $result['product_id'],
                    'event_id'    => $result['event_id'],
                    'thumb'       => $image,
                    'name'        => $result['name'],
                    'description' => $description,
                    'price'       => $price,
                    'special'     => $special,
                    'rating'      => $rating,
                    'reviews'     => sprintf(Lang::get('lang_text_reviews') , (int)$result['reviews']) ,
                    'href'        => Url::link('catalog/product', 'path=' . $result['paths'] . '&product_id=' . $result['product_id']) ,
                );
            }
            
            $cachefile = $masonry_products;
            Cache::set($key, $cachefile);
        }
        
        $data['products'] = $cachefile;
        
        $data['widget'] = $widget++;
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        return View::make('widget/masonry', $data);
    }
    
    protected function formatDescription($description, $chars = 100) {
        $description = preg_replace('/<[^>]+>/i', ' ', html_entity_decode($description, ENT_QUOTES, 'UTF-8'));
        
        if (Encode::strlen($description) > $chars) {
            return trim(Encode::substr($description, 0, $chars)) . '...';
        } else {
            return $description;
        }
    }
}
