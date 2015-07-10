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

use Dais\Services\Providers\Utility\Decorator;
use Dais\Engine\Container;
use Dais\Contracts\ServiceContract;

class DecoratorService implements ServiceContract {

	public function register(Container $app) {
		$app['decorator'] = function($app) {
            return new Decorator;
        };
	}
}
