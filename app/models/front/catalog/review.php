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

namespace App\Models\Front\Catalog;
use App\Models\Model;

class Review extends Model {
    public function addReview($product_id, $data) {
        DB::query("
			INSERT INTO " . DB::prefix() . "review 
			SET 
				author = '" . DB::escape($data['name']) . "', 
				customer_id = '" . (int)Customer::getId() . "', 
				product_id = '" . (int)$product_id . "', 
				text = '" . DB::escape($data['text']) . "', 
				rating = '" . (int)$data['rating'] . "', 
				date_added = NOW()
		");
        
        Theme::trigger('front_review_add', array('review_id' => DB::getLastId()));
    }
    
    public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
        if ($start < 0):
            $start = 0;
        endif;
        
        if ($limit < 1):
            $limit = 20;
        endif;
        
        $key = 'reviews.product.' . $product_id . '.' . $start . '.' . $limit;
        $cachefile = Cache::get($key);
        
        if (is_bool($cachefile)):
            $query = DB::query("
				SELECT 
					r.review_id, 
					r.author, 
					r.rating, 
					r.text, 
					p.product_id, 
					pd.name, 
					p.price, 
					p.image, 
					r.date_added 
				FROM " . DB::prefix() . "review r 
				LEFT JOIN " . DB::prefix() . "product p 
				ON (r.product_id = p.product_id) 
				LEFT JOIN " . DB::prefix() . "product_description pd 
				ON (p.product_id = pd.product_id) 
				WHERE p.product_id = '" . (int)$product_id . "' 
				AND p.date_available <= NOW() 
				AND p.status = '1' 
				AND r.status = '1' 
				AND pd.language_id = '" . (int)Config::get('config_language_id') . "' 
				ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
            
            if ($query->num_rows):
                $cachefile = $query->rows;
                Cache::set($key, $cachefile);
            else:
                Cache::set($key, array());
                return array();
            endif;
        endif;
        
        return $cachefile;
    }
    
    public function getTotalReviewsByProductId($product_id) {
        $key = 'reviews.product.total.' . $product_id;
        $cachefile = Cache::get($key);
        
        if (is_bool($cachefile)):
            $query = DB::query("
				SELECT COUNT(*) AS total 
				FROM " . DB::prefix() . "review r 
				LEFT JOIN " . DB::prefix() . "product p 
					ON (r.product_id = p.product_id) 
				LEFT JOIN " . DB::prefix() . "product_description pd 
					ON (p.product_id = pd.product_id) 
				WHERE p.product_id = '" . (int)$product_id . "' 
				AND p.date_available <= NOW() 
				AND p.status = '1' 
				AND r.status = '1' 
				AND pd.language_id = '" . (int)Config::get('config_language_id') . "'
			");
            
            $cachefile = $query->row['total'];
            Cache::set($key, $cachefile);
        endif;
        
        return $cachefile;
    }
}
