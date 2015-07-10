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

namespace Dais\Services\Utility;

use Dais\Services\Providers\Utility\Length;
use Dais\Base\Container;
use Dais\Contracts\ServiceContract;

class LengthService implements ServiceContract {

	public function register(Container $app) {
		$app['length'] = function ($app) {
            return new Length;
        };
	}
}