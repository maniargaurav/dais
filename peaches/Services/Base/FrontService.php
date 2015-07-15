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

use Dais\Base\Action;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Dais\Services\Providers\Base\Front;

class FrontService implements ServiceProviderInterface {

	public function register(Container $app) {
		
		$actions = Router::dispatch();

		$app['front'] = function ($app) use($actions) {
            $front = new Front;
	        $front->dispatch($actions['action'], $actions['error']);

            return $front;
        };
	}
}
