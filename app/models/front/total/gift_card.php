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

namespace App\Models\Front\Total;
use App\Models\Model;

class GiftCard extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        if (isset(Session::p()->data['gift_card'])):
            Lang::load('total/gift_card');
            
            Theme::model('checkout/gift_card');
            
            $gift_card_info = CheckoutGiftCard::getGiftcard(Session::p()->data['gift_card']);
            
            if ($gift_card_info):
                if ($gift_card_info['amount'] > $total):
                    $amount = $total;
                else:
                    $amount = $gift_card_info['amount'];
                endif;
                
                $total_data[] = array(
                    'code'       => 'gift_card', 
                    'title'      => sprintf(Lang::get('lang_text_gift_card'), Session::p()->data['gift_card']), 
                    'text'       => Currency::format(-$amount), 
                    'value'      => - $amount, 
                    'sort_order' => Config::get('gift_card_sort_order')
                );
                
                $total -= $amount;
            endif;
        endif;
    }
    
    public function confirm($order_info, $order_total) {
        $code  = '';
        $start = strpos($order_total['title'], '(') + 1;
        $end   = strrpos($order_total['title'], ')');
        
        if ($start && $end):
            $code = substr($order_total['title'], $start, $end - $start);
        endif;
        
        Theme::model('checkout/gift_card');
        
        $gift_card_info = CheckoutGiftCard::getGiftcard($code);
        
        if ($gift_card_info):
            CheckoutGiftCard::redeem($gift_card_info['gift_card_id'], $order_info['order_id'], $order_total['value']);
        endif;
    }
}
