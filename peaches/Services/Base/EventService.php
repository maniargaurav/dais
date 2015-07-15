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

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Dais\Services\Providers\Base\Event;

class EventService implements ServiceProviderInterface {

	public function register(Container $app) {
		
		$event  = new Event;
		$events = $event->registerEvents();

		$app->set('plugin_events', $events);

		$app['events'] = function ($app) use ($event) {
            return $event;
        };
	}
}
