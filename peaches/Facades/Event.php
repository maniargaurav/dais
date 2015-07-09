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

namespace Dais\Facades;

class Event extends Facade {
    public static function getInstanceIdentifier() {
        return 'events';
    }

    /**
     * Allows us to return properties
     * with Facade::p()->property
     */
    public static function p() {
    	return parent::getInstance();
    }
}
