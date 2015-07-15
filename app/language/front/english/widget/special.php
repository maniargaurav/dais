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

namespace App\Language\Front\English\Widget;

class Special {
	public static function lang() {
		// Heading
		$_['lang_heading_title'] = 'Specials';

		// Text
		$_['lang_text_reviews']  = 'Based on %s reviews.';

		return $_;
	}
}
