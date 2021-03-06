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

namespace App\Controllers\Admin\Common;

use App\Controllers\Controller;

class Logout extends Controller {
    
    public function index() {
        User::logout();
        
        unset(Session::p()->data['token']);
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        Response::redirect(Config::get('config_ssl'));
    }
}
