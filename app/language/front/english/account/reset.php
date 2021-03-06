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

namespace App\Language\Front\English\Account;

class Reset {
	public static function lang() {
		// header
		$_['lang_heading_title']  = 'Reset your password';

		// Text
		$_['lang_text_reset']     = 'Reset your password.';
		$_['lang_text_password']  = 'Enter your new password choice.';
		$_['lang_text_success']   = 'Success: Your password was successfully updated.';

		// Entry
		$_['lang_entry_password'] = 'Password:';
		$_['lang_entry_confirm']  = 'Password Confirm:';

		$_['lang_button_cancel']  = 'Cancel';

		// Error
		$_['lang_error_password'] = 'Password must be between 5 and 20 characters.';
		$_['lang_error_confirm']  = 'Password and password confirmation do not match.';

		return $_;
	}
}
