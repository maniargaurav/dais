<?php

/*
|--------------------------------------------------------------------------
|   Dais
|--------------------------------------------------------------------------
|
|   This file is part of the Dais Framework package.
|    
|   (c) Vince Kronlein <vince@dais.io>
|    
|   For the full copyright and license information, please view the LICENSE
|   file that was distributed with this source code.
|   
|   Your table prefix has been included so that you can easily write your 
|   migrations to include the proper prefix.
|   
|   $users = $this->create_table("{$this->prefix}users");
|
|   Obviously if you have no table prefix, this variable will be empty.
|   
*/

namespace Database\Migration;
use Egress\Library\Migration\MigrationBase;

class CreateAffiliateRoute_20150517070031 extends MigrationBase {

    private $prefix = 'dais_';

    public function up() {

        $table = $this->create_table("{$this->prefix}affiliate_route", array(
            'id'      => false, 
            'options' => 'Engine=InnoDB'
        ));

        $table->column('route_id', 'integer', array('primary_key' => true, 'unsigned' => true, 'auto_increment' => true));
        $table->column('route', 'string', array('primary_key' => true, 'limit' => 55));
        $table->column('query', 'string');
        $table->column('slug', 'string', array('primary_key' => true));

        $table->finish();
    }

    public function down() {
    	$this->drop_table("{$this->prefix}affiliate_route");
    }
}
