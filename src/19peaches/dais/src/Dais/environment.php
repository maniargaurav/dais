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

// Environment detection helpers ported from Laravel

namespace Dais;

class Environment {
	
	public static function detectEnvironments($environments) {
		foreach ($environments as $environment => $space):
			foreach($space['developers'] as $developer => $workspace):
				if (isset($_SERVER['SERVER_NAME'])):
					if (self::str_is($workspace['host'], $_SERVER['SERVER_NAME'])):
						return array(
							'environment' => $environment,
							'developer'   => $developer,
							'machine'     => $workspace['host']
						);
					endif;
				elseif (self::str_is($workspace['machine'], gethostname())):
					return array(
						'environment' => $environment,
						'developer'   => $developer,
						'machine'     => $workspace['host']
					);
				endif;
			endforeach;
		endforeach;
	}

	public static function str_is($pattern, $value) {
		if ($pattern == $value) return true;
		
		$pattern = preg_quote($pattern, '#');
		$pattern = str_replace('\*', '.*', $pattern) . '\z';

		return (bool) preg_match('#^' . $pattern . '#', $value);
	}
}

