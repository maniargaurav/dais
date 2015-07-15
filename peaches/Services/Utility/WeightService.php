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

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Dais\Services\Providers\Utility\Weight;

class WeightService implements ServiceProviderInterface {

	public function register(Container $app) {
		$app['weight'] = function ($app) {
            return new Weight;
        };
	}
}
