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

namespace Front\Model\Payment;
use Dais\Engine\Model;

class Freecheckout extends Model {
    public function getMethod($address, $total) {
        $this->language->load('payment/freecheckout');
        
        if ($total == 0) {
            $status = true;
        } else {
            $status = false;
        }
        
        $method_data = array();
        
        if ($status) {
            $method_data = array('code' => 'freecheckout', 'title' => $this->language->get('lang_text_title'), 'sort_order' => $this->config->get('freecheckout_sort_order'));
        }
        
        return $method_data;
    }
}
