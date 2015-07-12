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

namespace App\Language\Admin\English\Locale;

class ReturnAction {
	public static function lang() {
		// Heading
		$_['lang_heading_title']    = 'Return Action';

		// Text
		$_['lang_text_success']     = 'Success: You have modified return actions.';

		// Column
		$_['lang_column_name']      = 'Return Action Name';
		$_['lang_column_action']    = 'Action';

		// Entry
		$_['lang_entry_name']       = 'Return Action Name:';

		// Error
		$_['lang_error_permission'] = 'Warning: You do not have permission to modify return actions.';
		$_['lang_error_name']       = 'Return Action Name must be between 3 and 32 characters.';
		$_['lang_error_return']     = 'Warning: This return action cannot be deleted as it is currently assigned to %s returned products.';

		return $_;
	}
}
