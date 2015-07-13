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

namespace Front\Language\English\Widget;

class BestSeller {
	public static function lang() {
		// Heading
		$_['lang_heading_title'] = 'Bestsellers';

		// Text
		$_['lang_text_reviews']  = 'Based on %s reviews.';

		return $_;
	}
}
