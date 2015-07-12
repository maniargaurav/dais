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

namespace App\Models\Front\Shipping;
use App\Models\Model;

class Pickup extends Model {
    function getQuote($address) {
        Lang::load('shipping/pickup');
        
        $query = $this->db->query("
            SELECT * 
            FROM {$this->db->prefix}zone_to_geo_zone 
            WHERE geo_zone_id = '" . (int)Config::get('pickup_geo_zone_id') . "' 
            AND country_id    = '" . (int)$address['country_id'] . "' 
            AND (zone_id      = '" . (int)$address['zone_id'] . "' OR zone_id = '0')"
        );
        
        if (!Config::get('pickup_geo_zone_id')):
            $status = true;
        elseif ($query->num_rows):
            $status = true;
        else:
            $status = false;
        endif;
        
        $method_data = array();
        
        if ($status):
            $quote_data = array();
            
            $quote_data['pickup'] = array(
                'code'         => 'pickup.pickup', 
                'title'        => Lang::get('lang_text_description'), 
                'cost'         => 0.00, 
                'tax_class_id' => 0, 
                'text'         => $this->currency->format(0.00)
            );
            
            $method_data = array(
                'code'       => 'pickup', 
                'title'      => Lang::get('lang_text_title'), 
                'quote'      => $quote_data, 
                'sort_order' => Config::get('pickup_sort_order'), 
                'error'      => false
            );
        endif;
        
        return $method_data;
    }
}
