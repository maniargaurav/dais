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

namespace App\Models\Admin\Tool;

use App\Models\Model;

class Backup extends Model {
    
    public function restore($sql) {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);
            
            if ($sql) {
                DB::query($sql);
            }
        }
        
        Cache::flush_cache();
    }
    
    public function getTables() {
        $table_data = array();
        
        $query = DB::query("SHOW TABLES FROM `" . DB_DATABASE . "`");
        
        foreach ($query->rows as $result) {
            if (Encode::substr($result['Tables_in_' . DB_DATABASE], 0, strlen(DB_PREFIX)) == DB_PREFIX) {
                if (isset($result['Tables_in_' . DB_DATABASE])) {
                    $table_data[] = $result['Tables_in_' . DB_DATABASE];
                }
            }
        }
        
        return $table_data;
    }
    
    public function backup($tables) {
        $output = '';
        
        foreach ($tables as $table) {
            if (DB_PREFIX) {
                if (strpos($table, DB_PREFIX) === false) {
                    $status = false;
                } else {
                    $status = true;
                }
            } else {
                $status = true;
            }
            
            if ($status) {
                $output.= 'TRUNCATE TABLE `' . $table . '`;' . "\n\n";
                
                $query = DB::query("SELECT * FROM `{$table}`");
                
                foreach ($query->rows as $result) {
                    $fields = '';
                    
                    foreach (array_keys($result) as $value) {
                        $fields.= '`' . $value . '`, ';
                    }
                    
                    $values = '';
                    
                    foreach (array_values($result) as $value) {
                        $value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
                        $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
                        $value = str_replace('\\', '\\\\', $value);
                        $value = str_replace('\'', '\\\'', $value);
                        $value = str_replace('\\\n', '\n', $value);
                        $value = str_replace('\\\r', '\r', $value);
                        $value = str_replace('\\\t', '\t', $value);
                        
                        $values.= '\'' . $value . '\', ';
                    }
                    
                    $output.= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
                }
                
                $output.= "\n\n";
            }
        }
        
        Theme::trigger('admin_backup');
        
        return $output;
    }
}
