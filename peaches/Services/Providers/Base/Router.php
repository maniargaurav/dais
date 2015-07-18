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

namespace Dais\Services\Providers\Base;

class Router {

	public function dispatch() {

		if (!is_null(Request::get('route'))):
			
			$action = new Action(Request::get('route'));

        endif;

		if (!is_null(Request::get('_route_'))):

			$segments   = explode('/', Request::get('_route_'));
			
			$controller = count($segments) > 1 ? $segments[0] . '/' . $segments[1] : $segments[0];

			// This handles all of our search routing
            if ($segments[0] == 'search'):
                Request::get('route', 'search/search');

                if (end($segments) !== 'search'):
                    Request::post('search', end($segments));
                endif;

                $action = new Action(Request::get('route'));
            endif;
			
			// This handles all native files :not custom routes
			if (!is_null(Naming::file_from_route($controller))):
				Request::get('route', implode('/', $segments));

				$action = new Action(Request::get('route'));
			endif;
			
			// This handles any custom routes
			foreach (Routes::getCustomRoutes() as $key => $value):
                if ($key === Request::get('_route_')):
                    if (!is_null(Naming::file_from_route($value))):
                    	Request::get('route', $value);

						$action = new Action(Request::get('route'));
					endif;
                endif;
            endforeach;

            // This handles all slug routes
            $result = $this->iterate($segments);
			
			if (!empty($result)):
				Request::get('route', $result['controller']);

				$action = new Action(Request::get('route'));
			endif;

		endif;

		$error = new Action('error/not_found');

        switch (Config::get('active.facade')):
            case FRONT_FACADE:
                $default = new Action (Config::get('config_site_style') . '/home');
                break;
            case ADMIN_FACADE:
                $default = new Action('common/dashboard');
                break;
        endswitch;

        $actions['action'] = (!is_null(Request::get('route'))) ? $action : $default;
        $actions['error']  = $error;

        return $actions;
	}

	private function iterate($search) {
		
		$routes = Routes::allRoutes();
		
		$result = [];

		// Blog and Product Category Holders
		$blog    = false;
		$bpath   = '';

		$product = false;
		$path    = '';
		
		foreach($search as $segment):
			foreach($routes as $route):
				if ($route['slug'] === $segment):
					$items = explode(':', $route['query']);
					$result['controller'] = $route['route'];
					switch ($items[0]):
						case 'blog_category_id':
							$blog   = true;
							$bpath .= '_' . $items[1];
							break;
						case 'category_id':
							$product = true;
							$path   .= '_' . $items[1];
							break;
						case 'post_id':
							Request::get('post_id', $items[1]);
							break;
						case 'product_id':
							Request::get('product_id', $items[1]);
							break;
						case 'manufacturer_id':
							Request::get('manufacturer_id', $items[1]);
							break;
						case 'event_page_id':
							Request::get('event_page_id', $items[1]);
							break;
						case 'page_id':
							Request::get('page_id', $items[1]);
							break;
					endswitch;
				endif;
			endforeach;
		endforeach;

		if ($blog):
			Request::get('bpath', ltrim($bpath, '_'));
		endif;

		if ($product):
			Request::get('path',  ltrim($path, '_'));
		endif;

		return $result;
	}
}
