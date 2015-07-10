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

use Dais\Services\Providers\Utility\Validate;
use Dais\Base\Container;
use Dais\Contracts\ServiceContract;

class ValidateService implements ServiceContract {

	public function register(Container $app) {
		$app['validator'] = function ($app) {
            return new Validate;
        };
	}
}
