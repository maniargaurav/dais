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

	protected static $current;
	protected static $route;

	public function dispatch() {

		if (!is_null(Request::get('route'))):
			Response::test(Request::get('route'));
		endif;

		if (!is_null(Request::get('_route_'))):

			static::$current = Request::get('_route_');

			$segments = explode('/', str_replace('-', '_', static::$current));
			
			// slug specific array
			$parts    = explode('/', static::$current);
			
			$controller = count($segments) > 1 ? $segments[0] . '/' . $segments[1] : $segments[0];

			// This handles all of our search routing
            if ($segments[0] == 'search'):
                Request::get('route', 'search/search');

                static::$route = 'search/search';

                if (end($segments) !== 'search'):
                    Request::post('search', end($segments));
                endif;
            endif;

            // Slugs and Custom routes for front module only
            if (!static::$route && Config::get('active.facade') === FRONT_FACADE):
            	foreach (Routes::getCustomRoutes() as $key => $value):
	                if ($key === static::$current):
	                    if (Finder::find($value)):
	                    	static::$route = $value;
						endif;
	                endif;
	            endforeach;

	            if (!static::$route):
		            $result = $this->iterate($parts);
					
					if (!empty($result)):
						static::$route = $result['controller'];
					endif;
				endif;
            endif;
            
			// This handles all native files :not custom routes
			if (!static::$route):
				if (Finder::find($controller)):
					$args = (count($segments) % 2) ? static::to_assoc(3) : static::to_assoc(2);
					
					foreach($args as $key => $value):
						Request::get($key, $value);
					endforeach;
					
				endif;
			endif;
		else:
			switch (Config::get('active.facade')):
	            case FRONT_FACADE:
	                $default = Config::get('config_site_style') . '/home';
	                break;
	            case ADMIN_FACADE:
	                $default = 'common/dashboard';
	                break;
	        endswitch;
		endif;

        if (is_null(static::$route) && is_null(static::$current)):
        	static::$route = $default;
        endif;
        
        Request::get('route', static::$route);

        $actions['action'] = new Action(static::$route);
        $actions['error']  = new Action('error/not_found');
        
        return $actions;
	}

	private function iterate($search) {
		
		$routes = Routes::allRoutes();
		
		$result = [];

		// Blog and Product Category Holders
		$blog  = false;
		$bpath = '';

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

	public static function to_assoc($offset = 0) {
		$segments  = explode('/', static::$current);
		$arguments = [];
		$route     = [];

		for ($i = 0; $i < $offset; $i++):
			$route[] = array_shift($segments);
		endfor;
		
		$length = count($segments);

		static::$route = implode('/', $route);

		for ($i = 0; $i < $length; $i = $i + 2):
			$arguments[str_replace('-', '_', $segments[$i])] = $segments[$i + 1];
		endfor;
		
		return $arguments;
	}
}
