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

namespace App\Language\Admin\English\Total;

class Tax {
	public static function lang() {
		// Heading
		$_['lang_heading_title']    = 'Taxes';

		// Text
		$_['lang_text_total']       = 'Order Totals';
		$_['lang_text_success']     = 'Success: You have modified taxes total.';

		// Entry
		$_['lang_entry_status']     = 'Status:';
		$_['lang_entry_sort_order'] = 'Sort Order:';

		// Error
		$_['lang_error_permission'] = 'Warning: You do not have permission to modify taxes total.';

		return $_;
	}
}
