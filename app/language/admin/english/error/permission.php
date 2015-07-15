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

namespace App\Language\Admin\English\Error;

class Permission {
	public static function lang() {
		// Heading
		$_['lang_heading_title']   = 'Permission Denied.';

		// Text
		$_['lang_text_permission'] = 'You do not have permission to access this page, please refer to your system administrator.';

		return $_;
	}
}
