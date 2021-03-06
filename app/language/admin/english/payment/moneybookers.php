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

namespace App\Language\Admin\English\Payment;

class Moneybookers {
	public static function lang() {
		// Heading
		$_['lang_heading_title']           = 'Moneybookers';

		// Text
		$_['lang_text_payment']            = 'Payment';
		$_['lang_text_success']            = 'Success: You have modified the Moneybookers details.';

		// Entry
		$_['lang_entry_email']             = 'E-Mail:';
		$_['lang_entry_secret']            = 'Secret:';
		$_['lang_entry_total']             = 'Total:<br /><span class="help">The checkout total the order must reach before this payment method becomes active.</span>';
		$_['lang_entry_order_status']      = 'Order Status:';
		$_['lang_entry_pending_status']    = 'Pending Status :';
		$_['lang_entry_canceled_status']   = 'Canceled Status:';
		$_['lang_entry_failed_status']     = 'Failed Status:';
		$_['lang_entry_chargeback_status'] = 'Charge Back Status:';
		$_['lang_entry_geo_zone']          = 'Geo Zone:';
		$_['lang_entry_status']            = 'Status:';
		$_['lang_entry_sort_order']        = 'Sort Order:';

		// Error
		$_['lang_error_permission']        = 'Warning: You do not have permission to modify Moneybookers.';
		$_['lang_error_email']             = 'E-Mail Required.';

		return $_;
	}
}
