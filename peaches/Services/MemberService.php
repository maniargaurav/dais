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

namespace Dais\Services;

use Dais\Services\Providers\User;
use Dais\Services\Providers\Customer;
use Dais\Services\Providers\Tax;
use Dais\Services\Providers\Cart;
use Dais\Engine\Container;
use Dais\Contracts\ServiceContract;

class MemberService implements ServiceContract {

	public function register(Container $app) {
		/**
		 * Here we'll load either admin services or customer
		 * services based on which module we're working with.
		 */
		
		if (Config::get('active.facade') === ADMIN_FACADE):
			
			$app['user'] = function ($app) {
	            return new User;
	        };

		else:

			$app['customer'] = function ($app) {
	            return new Customer;
	        };

	        // Weird spot for this, but let's set our affiliate cookie.
	        if (isset(Request::p()->get['z'])):
	        	$this->affiliate();
	        endif;

	        $app['tax'] = function ($app) {
	            return new Tax;
	        };

	        $app['cart'] = function ($app) {
	            return new Cart;
	        };

		endif;
	}

	private function affiliate() {
        $query = DB::query("
            SELECT customer_id 
            FROM " . DB::p()->prefix . "customer 
            WHERE code = '" . DB::escape(Request::p()->get['z']) . "' 
            AND is_affiliate = '1' 
            AND affiliate_status = '1'
        ");

        if ($query->num_rows):
            setcookie('affiliate_id', $query->row['customer_id'], time() + ((3600 * 24) * 365), '/');
        endif;
	}
}
