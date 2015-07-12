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
use Dais\Driver\Cache\Apc;
use Dais\Driver\Cache\File;
use Dais\Driver\Cache\Mem;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CacheService implements ServiceProviderInterface {

	public function register(Container $app) {
        $app['cache'] = function($app) {
            switch (Config::get('config_cache_type_id')):
                case 'apc':
                    $driver = new Apc;
                    break;
                case 'mem':
                    $driver = new Mem;
                    $driver->connect();
                    break;
                case 'file':
                    $driver = new File;
                    break;
            endswitch;

            return new Cache($driver);
        };
	}
}
