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

namespace Dais\Services\Communication;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Dais\Services\Providers\Communication\Keyword;

class KeywordService implements ServiceProviderInterface {

	public function register(Container $app) {
		$app['keyword'] = function ($app) {
            return new Keyword;
        };
	}
}
