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

namespace App\Models\Admin\Payment;

use App\Models\Model;

class PaypalProIframe extends Model {
    
    public function install() {
        DB::query("
			CREATE TABLE IF NOT EXISTS `" . DB::prefix() . "paypal_iframe_order` (
			  `paypal_iframe_order_id` int(11) NOT NULL AUTO_INCREMENT,
			  `order_id` int(11) NOT NULL,
			  `created` DATETIME NOT NULL,
			  `modified` DATETIME NOT NULL,
			  `capture_status` ENUM('Complete','NotComplete') DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `authorization_id` VARCHAR(30) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`paypal_iframe_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
        
        DB::query("
			CREATE TABLE IF NOT EXISTS `" . DB::prefix() . "paypal_iframe_order_transaction` (
			  `paypal_iframe_order_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
			  `paypal_iframe_order_id` int(11) NOT NULL,
			  `transaction_id` CHAR(20) NOT NULL,
			  `parent_transaction_id` CHAR(20) NOT NULL,
			  `created` DATETIME NOT NULL,
			  `note` VARCHAR(255) NOT NULL,
			  `msgsubid` CHAR(38) NOT NULL,
			  `receipt_id` CHAR(20) NOT NULL,
			  `payment_type` ENUM('none','echeck','instant', 'refund', 'void') DEFAULT NULL,
			  `payment_status` CHAR(20) NOT NULL,
			  `pending_reason` CHAR(50) NOT NULL,
			  `transaction_entity` CHAR(50) NOT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  `debug_data` TEXT NOT NULL,
			  `call_data` TEXT NOT NULL,
			  PRIMARY KEY (`paypal_iframe_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
    }
    
    public function uninstall() {
        DB::query("DROP TABLE IF EXISTS `" . DB::prefix() . "paypal_iframe_order_transaction`;");
        DB::query("DROP TABLE IF EXISTS `" . DB::prefix() . "paypal_iframe_order`;");
    }
    
    private function getTransactions($paypal_iframe_order_id) {
        $qry = DB::query("SELECT `ot`.*, ( SELECT count(`ot2`.`paypal_iframe_order_id`) FROM `" . DB::prefix() . "paypal_iframe_order_transaction` `ot2` WHERE `ot2`.`parent_transaction_id` = `ot`.`transaction_id` ) AS `children` FROM `" . DB::prefix() . "paypal_iframe_order_transaction` `ot` WHERE `paypal_iframe_order_id` = '" . (int)$paypal_iframe_order_id . "'");
        
        if ($qry->num_rows) {
            return $qry->rows;
        } else {
            return false;
        }
    }
    
    public function totalCaptured($paypal_iframe_order_id) {
        $qry = DB::query("SELECT SUM(`amount`) AS `amount` FROM `" . DB::prefix() . "paypal_iframe_order_transaction` WHERE `paypal_iframe_order_id` = '" . (int)$paypal_iframe_order_id . "' AND `pending_reason` != 'authorization' AND (`payment_status` = 'Partially-Refunded' OR `payment_status` = 'Completed' OR `payment_status` = 'Pending') AND `transaction_entity` = 'payment'");
        
        return $qry->row['amount'];
    }
    
    public function totalRefundedOrder($paypal_iframe_order_id) {
        $qry = DB::query("SELECT SUM(`amount`) AS `amount` FROM `" . DB::prefix() . "paypal_iframe_order_transaction` WHERE `paypal_iframe_order_id` = '" . (int)$paypal_iframe_order_id . "' AND `payment_status` = 'Refunded'");
        
        return $qry->row['amount'];
    }
    
    public function totalRefundedTransaction($transaction_id) {
        $qry = DB::query("SELECT SUM(`amount`) AS `amount` FROM `" . DB::prefix() . "paypal_iframe_order_transaction` WHERE `parent_transaction_id` = '" . DB::escape($transaction_id) . "' AND `payment_type` = 'refund'");
        
        return $qry->row['amount'];
    }
    
    public function getOrder($order_id) {
        $qry = DB::query("SELECT * FROM `" . DB::prefix() . "paypal_iframe_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");
        
        if ($qry->num_rows) {
            $order = $qry->row;
            $order['transactions'] = $this->getTransactions($order['paypal_iframe_order_id']);
            $order['captured'] = $this->totalCaptured($order['paypal_iframe_order_id']);
            return $order;
        } else {
            return false;
        }
    }
    
    public function call($data) {
        
        if (Config::get('paypal_pro_iframe_test') == 1) {
            $api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
        } else {
            $api_endpoint = 'https://api-3t.paypal.com/nvp';
        }
        
        $settings = array('USER' => Config::get('paypal_pro_iframe_user'), 'PWD' => Config::get('paypal_pro_iframe_password'), 'SIGNATURE' => Config::get('paypal_pro_iframe_sig'), 'VERSION' => '84', 'BUTTONSOURCE' => 'WM_PRO_Dais_UK_' . VERSION,);
        
        Log::write($data, 'Call data');
        
        $defaults = array(CURLOPT_POST => 1, CURLOPT_HEADER => 0, CURLOPT_URL => $api_endpoint, CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1", CURLOPT_FRESH_CONNECT => 1, CURLOPT_RETURNTRANSFER => 1, CURLOPT_FORBID_REUSE => 1, CURLOPT_TIMEOUT => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_POSTFIELDS => http_build_query(array_merge($data, $settings), '', "&"));
        
        $ch = curl_init();
        
        curl_setopt_array($ch, $defaults);
        
        if (!$result = curl_exec($ch)) {
            
            $log_data = array('curl_error' => curl_error($ch), 'curl_errno' => curl_errno($ch));
            
            Log::write($log_data, 'CURL failed');
            
            return false;
        }
        
        Log::write($result, 'Result');
        
        curl_close($ch);
        
        return $this->cleanReturn($result);
    }
    
    public function updateOrder($capture_status, $order_id) {
        DB::query("UPDATE `" . DB::prefix() . "paypal_iframe_order` SET `modified` = now(), `capture_status` = '" . DB::escape($capture_status) . "' WHERE `order_id` = '" . (int)$order_id . "'");
    }
    
    public function updateTransaction($transaction) {
        DB::query("
			UPDATE " . DB::prefix() . "paypal_iframe_order_transaction
			SET paypal_iframe_order_id = " . (int)$transaction['paypal_iframe_order_id'] . ",
				transaction_id = '" . DB::escape($transaction['transaction_id']) . "',
				parent_transaction_id = '" . DB::escape($transaction['parent_transaction_id']) . "',
				created = '" . DB::escape($transaction['created']) . "',
				note = '" . DB::escape($transaction['note']) . "',
				msgsubid = '" . DB::escape($transaction['msgsubid']) . "',
				receipt_id = '" . DB::escape($transaction['receipt_id']) . "',
				payment_type = '" . DB::escape($transaction['payment_type']) . "',
				payment_status = '" . DB::escape($transaction['payment_status']) . "',
				pending_reason = '" . DB::escape($transaction['pending_reason']) . "',
				transaction_entity = '" . DB::escape($transaction['transaction_entity']) . "',
				amount = '" . DB::escape($transaction['amount']) . "',
				debug_data = '" . DB::escape($transaction['debug_data']) . "',
				call_data = '" . DB::escape($transaction['call_data']) . "'
			WHERE paypal_iframe_order_transaction_id = " . (int)$transaction['paypal_iframe_order_transaction_id'] . "
		");
    }
    
    public function addTransaction($transaction_data, $request_data = array()) {
        DB::query("INSERT INTO `" . DB::prefix() . "paypal_iframe_order_transaction` SET `paypal_iframe_order_id` = '" . (int)$transaction_data['paypal_iframe_order_id'] . "', `transaction_id` = '" . DB::escape($transaction_data['transaction_id']) . "', `parent_transaction_id` = '" . DB::escape($transaction_data['parent_transaction_id']) . "', `created` = NOW(), `note` = '" . DB::escape($transaction_data['note']) . "', `msgsubid` = '" . DB::escape($transaction_data['msgsubid']) . "', `receipt_id` = '" . DB::escape($transaction_data['receipt_id']) . "', `payment_type` = '" . DB::escape($transaction_data['payment_type']) . "', `payment_status` = '" . DB::escape($transaction_data['payment_status']) . "', `pending_reason` = '" . DB::escape($transaction_data['pending_reason']) . "', `transaction_entity` = '" . DB::escape($transaction_data['transaction_entity']) . "', `amount` = '" . (double)$transaction_data['amount'] . "', `debug_data` = '" . DB::escape($transaction_data['debug_data']) . "'");
        
        $paypal_iframe_order_transaction_id = DB::getLastId();
        
        if ($request_data) {
            $serialized_data = serialize($request_data);
            
            DB::query("
				UPDATE " . DB::prefix() . "paypal_iframe_order_transaction
				SET call_data = '" . DB::escape($serialized_data) . "'
				WHERE paypal_iframe_order_transaction_id = " . (int)$paypal_iframe_order_transaction_id . "
				LIMIT 1
			");
        }
        
        return $paypal_iframe_order_transaction_id;
    }
    
    public function log($data, $title = null) {
        if (Config::get('paypal_pro_iframe_debug')) {
            $log = new Log('paypal_pro_iframe.log');
            $log->write($title . ': ' . json_encode($data));
        }
    }
    
    public function getTransaction($transaction_id) {
        $call_data = array('METHOD' => 'GetTransactionDetails', 'TRANSACTIONID' => $transaction_id,);
        
        return $this->call($call_data);
    }
    
    public function getOrderId($transaction_id) {
        $qry = DB::query("SELECT `o`.`order_id` FROM `" . DB::prefix() . "paypal_iframe_order_transaction` `ot` LEFT JOIN `" . DB::prefix() . "paypal_iframe_order` `o`  ON `o`.`paypal_iframe_order_id` = `ot`.`paypal_iframe_order_id`  WHERE `ot`.`transaction_id` = '" . DB::escape($transaction_id) . "' LIMIT 1");
        
        if ($qry->num_rows) {
            return $qry->row['order_id'];
        } else {
            return false;
        }
    }
    
    public function updateAuthorizationId($paypal_iframe_order_id, $authorization_id) {
        DB::query("
			UPDATE `" . DB::prefix() . "paypal_iframe_order`
			SET `authorization_id` = '" . DB::escape($authorization_id) . "'
			WHERE `paypal_iframe_order_id` = '" . DB::escape($paypal_iframe_order_id) . "'
		");
    }
    
    public function updateRefundTransaction($transaction_id, $transaction_type) {
        DB::query("UPDATE `" . DB::prefix() . "paypal_iframe_order_transaction` SET `payment_status` = '" . DB::escape($transaction_type) . "' WHERE `transaction_id` = '" . DB::escape($transaction_id) . "' LIMIT 1");
    }
    
    public function getFailedTransaction($paypl_iframe_order_transaction_id) {
        $result = DB::query("
			SELECT *
			FROM " . DB::prefix() . "paypal_iframe_order_transaction
			WHERE paypal_iframe_order_transaction_id = " . (int)$paypl_iframe_order_transaction_id . "
		")->row;
        
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    
    public function getLocalTransaction($transaction_id) {
        $result = DB::query("
			SELECT *
			FROM " . DB::prefix() . "paypal_iframe_order_transaction
			WHERE transaction_id = '" . DB::escape($transaction_id) . "'
		")->row;
        
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    
    protected function cleanReturn($data) {
        $data = explode('&', $data);
        
        $arr = array();
        
        foreach ($data as $k => $v) {
            $tmp = explode('=', $v);
            $arr[$tmp[0]] = urldecode($tmp[1]);
        }
        
        return $arr;
    }
}
