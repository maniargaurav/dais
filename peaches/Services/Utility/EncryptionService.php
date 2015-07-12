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

use Dais\Services\Providers\Utility\Encryption;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class EncryptionService implements ServiceProviderInterface {

	public function register(Container $app) {
		$app['encryption'] = function ($app) {
            return new Encryption(Config::get('config_encryption'));
        };
	}
}
