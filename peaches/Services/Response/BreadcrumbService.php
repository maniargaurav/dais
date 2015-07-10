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

namespace Dais\Services\Response;

use Dais\Services\Providers\Response\Breadcrumb;
use Dais\Base\Container;
use Dais\Contracts\ServiceContract;

class BreadcrumbService implements ServiceContract {

	public function register(Container $app) {
		$app['breadcrumb'] = function ($app) {
            return new Breadcrumb;
        };
	}
}
