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

namespace Dais\Services\Base;

use Dais\Services\Providers\Base\Plugin;
use Dais\Base\Container;
use Dais\Contracts\ServiceContract;

class PluginService implements ServiceContract {

	public function register(Container $app) {
		$app['plugin'] = function ($app) {
            return new Plugin;
        };
	}
}
