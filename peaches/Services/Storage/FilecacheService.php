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

namespace Dais\Services\Storage;

use Dais\Services\Providers\Storage\Cache;
use Dais\Driver\Cache\Asset;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class FilecacheService implements ServiceProviderInterface {

	public function register(Container $app) {
		$app['filecache'] = function ($app) {
            // 1 year in seconds to match htaccess rules
            return new Cache(new Asset(31536000));
        };
	}
}