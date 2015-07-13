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

namespace Admin\Language\English\Widget;

class BlogHotTopics {
	public static function lang() {
		// Heading
		$_['lang_heading_title']    = 'Hot Topics';

		// Text
		$_['lang_text_widget']      = 'Widgets';
		$_['lang_text_success']     = 'Success: You have modified hot topics.';

		// Entry
		$_['lang_entry_limit']      = 'Limit:';
		$_['lang_entry_layout']     = 'Layout:';
		$_['lang_entry_position']   = 'Position:';
		$_['lang_entry_status']     = 'Status:';
		$_['lang_entry_sort_order'] = 'Sort Order:';

		// Error
		$_['lang_error_permission'] = 'Warning: You do not have permission to modify hot topics.';

		return $_;
	}
}
