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

namespace App\Models\Front\Design;
use App\Models\Model;

class Layout extends Model {
    public function getLayout($route) {
        $key = 'layoutid.' . str_replace('/', '.', $route);
        $cachefile = Cache::get($key);
        
        if (is_bool($cachefile)):
            $query = DB::query("
				SELECT * 
				FROM " . DB::prefix() . "layout_route 
				WHERE '" . DB::escape($route) . "' 
				LIKE CONCAT(route, '%') 
				AND store_id = '" . (int)Config::get('config_store_id') . "' 
				ORDER BY route DESC LIMIT 1");
            
            if ($query->num_rows):
                $cachefile = $query->row['layout_id'];
                Cache::set($key, $cachefile);
            else:
                Cache::set($key, 0);
                return 0;
            endif;
        endif;
        
        return $cachefile;
    }
}
