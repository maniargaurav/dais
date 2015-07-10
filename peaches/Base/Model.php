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

namespace Dais\Base;

abstract class Model {
    
    public function __get($key) {
        return App::get($key);
    }
    
    public function __set($key, $value) {
        App::set($key, $value);
    }
}
