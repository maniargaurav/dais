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

class BreadCrumb extends Controller {
    
    public function index() {
        
        $data['breadcrumbs'] = \Breadcrumb::fetch();
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        return View::make('common/bread_crumb', $data);
    }
}
