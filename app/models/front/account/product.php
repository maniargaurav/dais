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

namespace App\Models\Front\Account;
use App\Models\Model;

class Product extends Model {
    public function getProduct($product_id, $customer_id) {
        if (\Customer::isLogged()):
            $customer_group_id = \Customer::getGroupId();
        else:
            $customer_group_id = Config::get('config_default_visibility');
        endif;
        
        $key = 'product.' . $product_id . '.' . $customer_id;
        $cachefile = Cache::get($key);
        
        if (is_bool($cachefile)):
            $query = DB::query("
				SELECT DISTINCT *, 
					pd.name AS name, 
					p.image, 
					m.name AS manufacturer, 
					(SELECT price 
						FROM " . DB::prefix() . "product_discount pd2 
						WHERE pd2.product_id = p.product_id 
						AND pd2.customer_group_id = '" . (int)$customer_group_id . "' 
						AND pd2.quantity = '1' 
						AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) 
						AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) 
						ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
					(SELECT price 
						FROM " . DB::prefix() . "product_special ps 
						WHERE ps.product_id = p.product_id 
						AND ps.customer_group_id = '" . (int)$customer_group_id . "' 
						AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) 
						AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) 
						ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, 
					(SELECT points 
						FROM " . DB::prefix() . "product_reward pr 
						WHERE pr.product_id = p.product_id 
						AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, 
					(SELECT ss.name 
						FROM " . DB::prefix() . "stock_status ss 
						WHERE ss.stock_status_id = p.stock_status_id 
						AND ss.language_id = '" . (int)Config::get('config_language_id') . "') AS stock_status, 
					(SELECT wcd.unit 
						FROM " . DB::prefix() . "weight_class_description wcd 
						WHERE p.weight_class_id = wcd.weight_class_id 
						AND wcd.language_id = '" . (int)Config::get('config_language_id') . "') AS weight_class, 
					(SELECT lcd.unit 
						FROM " . DB::prefix() . "length_class_description lcd 
						WHERE p.length_class_id = lcd.length_class_id 
						AND lcd.language_id = '" . (int)Config::get('config_language_id') . "') AS length_class, 
					(SELECT AVG(rating) AS total 
						FROM " . DB::prefix() . "review r1 
						WHERE r1.product_id = p.product_id 
						AND r1.status = '1' 
						GROUP BY r1.product_id) AS rating, 
					(SELECT COUNT(*) AS total 
						FROM " . DB::prefix() . "review r2 
						WHERE r2.product_id = p.product_id 
						AND r2.status = '1' 
						GROUP BY r2.product_id) AS reviews, 
					p.sort_order 
				FROM " . DB::prefix() . "product p 
				LEFT JOIN " . DB::prefix() . "product_description pd 
					ON (p.product_id = pd.product_id) 
				LEFT JOIN " . DB::prefix() . "product_to_store p2s 
					ON (p.product_id = p2s.product_id) 
				LEFT JOIN " . DB::prefix() . "manufacturer m 
					ON (p.manufacturer_id = m.manufacturer_id) 
				WHERE p.product_id = '" . (int)$product_id . "' 
				AND pd.language_id = '" . (int)Config::get('config_language_id') . "' 
				AND p.status = '1' 
				AND p.date_available <= NOW() 
				AND p.visibility <= '" . (int)$customer_group_id . "' 
				AND p.customer_id = '" . (int)$customer_id . "' 
				AND p2s.store_id = '" . (int)Config::get('config_store_id') . "'
			");
            
            if ($query->num_rows):
                $product = array(
					'product_id'       => $query->row['product_id'], 
					'name'             => $query->row['name'], 
					'description'      => $query->row['description'], 
					'meta_description' => $query->row['meta_description'], 
					'meta_keyword'     => $query->row['meta_keyword'], 
					'tag'              => $query->row['tag'], 
					'model'            => $query->row['model'], 
					'sku'              => $query->row['sku'], 
					'upc'              => $query->row['upc'], 
					'ean'              => $query->row['ean'], 
					'jan'              => $query->row['jan'], 
					'isbn'             => $query->row['isbn'], 
					'mpn'              => $query->row['mpn'], 
					'location'         => $query->row['location'], 
					'visibility'       => $query->row['visibility'], 
					'quantity'         => $query->row['quantity'], 
					'stock_status'     => $query->row['stock_status'], 
					'image'            => $query->row['image'], 
					'manufacturer_id'  => $query->row['manufacturer_id'], 
					'manufacturer'     => $query->row['manufacturer'], 
					'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']), 
					'special'          => $query->row['special'], 
					'reward'           => $query->row['reward'], 
					'points'           => $query->row['points'], 
					'tax_class_id'     => $query->row['tax_class_id'], 
					'date_available'   => $query->row['date_available'], 
					'weight'           => $query->row['weight'], 
					'weight_class_id'  => $query->row['weight_class_id'], 
					'length'           => $query->row['length'], 
					'width'            => $query->row['width'], 
					'height'           => $query->row['height'], 
					'length_class_id'  => $query->row['length_class_id'], 
					'subtract'         => $query->row['subtract'], 
					'rating'           => round($query->row['rating']), 
					'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0, 
					'minimum'          => $query->row['minimum'], 
					'sort_order'       => $query->row['sort_order'], 
					'status'           => $query->row['status'], 
					'date_added'       => $query->row['date_added'], 
					'event_id'         => (isset($query->row['event_id']) ? $query->row['event_id'] : 0), 
					'date_modified'    => $query->row['date_modified'], 
					'viewed'           => $query->row['viewed'], 
					'customer_id'      => $query->row['customer_id'],
					'paths'            => $this->buildPaths($query->row['product_id'])
                );
                
                $cachefile = $product;
                Cache::set($key, $cachefile);
            else:
                Cache::set($key, 0);
                return false;
            endif;
        endif;
        
        return $cachefile;
    }

    public function buildPaths($product_id) {
        $query = DB::query("
            SELECT category_id 
            FROM " . DB::prefix() . "product_to_category 
            WHERE product_id ='" . (int)$product_id . "' 
            ORDER BY category_id ASC
        ");

        $segments = [];

        if ($query->num_rows):
            foreach($query->rows as $category):
                $segments[] = $category['category_id'];
            endforeach;
        endif;

        return implode('_', $segments);
    }
}
