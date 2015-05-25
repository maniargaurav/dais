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

class CreateCategoryPath_20150524063253 extends MigrationBase {

    private $prefix = 'dais_';

    public function up() {
        $table = $this->create_table("{$this->prefix}category_path", array(
            'id'      => false, 
            'options' => 'Engine=InnoDB'
        ));

        $table->column('category_id', 'integer', array('unsigned' => true, 'primary_key' => true));
        $table->column('path_id', 'integer', array('unsigned' => true, 'primary_key' => true));
        $table->column('level', 'integer', array('unsigned' => true));
        
        $table->finish();

        $sql = "INSERT INTO `{$this->prefix}category_path` (`category_id`, `path_id`, `level`) VALUES
                (17, 17, 0),
                (18, 18, 0),
                (20, 20, 0),
                (24, 24, 0),
                (25, 25, 0),
                (26, 20, 0),
                (26, 26, 1),
                (27, 20, 0),
                (27, 27, 1),
                (28, 25, 0),
                (28, 28, 1),
                (29, 25, 0),
                (29, 29, 1),
                (30, 25, 0),
                (30, 30, 1),
                (31, 25, 0),
                (31, 31, 1),
                (32, 25, 0),
                (32, 32, 1),
                (33, 33, 0),
                (34, 34, 0),
                (35, 25, 0),
                (35, 28, 1),
                (35, 35, 2),
                (36, 25, 0),
                (36, 28, 1),
                (36, 36, 2),
                (37, 34, 0),
                (37, 37, 1),
                (38, 34, 0),
                (38, 38, 1),
                (39, 34, 0),
                (39, 39, 1),
                (40, 34, 0),
                (40, 40, 1),
                (41, 34, 0),
                (41, 41, 1),
                (42, 34, 0),
                (42, 42, 1),
                (43, 34, 0),
                (43, 43, 1),
                (44, 34, 0),
                (44, 44, 1),
                (45, 18, 0),
                (45, 45, 1),
                (46, 18, 0),
                (46, 46, 1),
                (47, 34, 0),
                (47, 47, 1),
                (48, 34, 0),
                (48, 48, 1),
                (49, 34, 0),
                (49, 49, 1),
                (50, 34, 0),
                (50, 50, 1),
                (51, 34, 0),
                (51, 51, 1),
                (52, 34, 0),
                (52, 52, 1),
                (53, 34, 0),
                (53, 53, 1),
                (54, 34, 0),
                (54, 54, 1),
                (55, 34, 0),
                (55, 55, 1),
                (56, 34, 0),
                (56, 56, 1),
                (57, 57, 0),
                (58, 34, 0),
                (58, 52, 1),
                (58, 58, 2)";

        $this->execute($sql);
    }

    public function down() {
        $this->drop_table("{$this->prefix}category_path");
    }
}
