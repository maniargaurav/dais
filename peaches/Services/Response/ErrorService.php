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

use Dais\Services\Providers\Response\Error;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ErrorService implements ServiceProviderInterface {

	public function register(Container $app) {
		$app['errorhandler'] = function ($app) {
            $error = new Error;

            set_error_handler(array(
	            $error,
	            'error_handler'
	        ));

            return $error;
        };
	}
}
