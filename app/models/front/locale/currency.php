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

namespace App\Models\Front\Locale;
use App\Models\Model;

class Currency extends Model {
    public function getCurrencyByCode($currency) {
        $key = 'currency.' . $currency;
        $cachefile = $this->cache->get($key);
        
        if (is_bool($cachefile)):
            $query = DB::query("
				SELECT DISTINCT * 
				FROM " . DB::prefix() . "currency 
				WHERE code = '" . DB::escape($currency) . "'
			");
            
            if ($query->num_rows):
                $cachefile = $query->row;
                $this->cache->set($key, $cachefile);
            else:
                $this->cache->set($key, array());
                return array();
            endif;
        endif;
        
        return $cachefile;
    }
    
    public function getCurrencies() {
        $key = 'currency.all.' . (int)Config::get('config_store_id');
        $cachefile = $this->cache->get($key);
        
        if (is_bool($cachefile)) {
            $currency_data = array();
            
            $query = DB::query("SELECT * FROM " . DB::prefix() . "currency ORDER BY title ASC");
            
            foreach ($query->rows as $result) {
                $currency_data[$result['code']] = array('currency_id' => $result['currency_id'], 'title' => $result['title'], 'code' => $result['code'], 'symbol_left' => $result['symbol_left'], 'symbol_right' => $result['symbol_right'], 'decimal_place' => $result['decimal_place'], 'value' => $result['value'], 'status' => $result['status'], 'date_modified' => $result['date_modified']);
            }
            
            $cachefile = $currency_data;
            $this->cache->set($key, $cachefile);
        }
        
        return $cachefile;
    }
}
