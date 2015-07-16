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

namespace App\Models\Front\Payment;
use App\Models\Model;

class PaypalStandard extends Model {
    public function getMethod($address, $total) {
        Lang::load('payment/paypal_standard');
        
        $query = DB::query("SELECT * FROM " . DB::prefix() . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)Config::get('paypal_standard_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
        
        if (Config::get('paypal_standard_total') > $total) {
            $status = false;
        } elseif (!Config::get('paypal_standard_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }
        
        $currencies = array('AUD', 'CAD', 'EUR', 'GBP', 'JPY', 'USD', 'NZD', 'CHF', 'HKD', 'SGD', 'SEK', 'DKK', 'PLN', 'NOK', 'HUF', 'CZK', 'ILS', 'MXN', 'MYR', 'BRL', 'PHP', 'TWD', 'THB', 'TRY');
        
        if (!in_array(strtoupper(Currency::getCode()), $currencies)) {
            $status = false;
        }
        
        $method_data = array();
        
        if ($status) {
            $method_data = array('code' => 'paypal_standard', 'title' => Lang::get('lang_text_title'), 'sort_order' => Config::get('paypal_standard_sort_order'));
        }
        
        return $method_data;
    }
}
