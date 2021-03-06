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

namespace App\Models\Admin\Catalog;

use App\Models\Model;

class Recurring extends Model {
    
    public function addRecurring($data) {
        DB::query("
			INSERT INTO " . DB::prefix() . "recurring 
			SET 
                sort_order      = '" . (int)$data['sort_order'] . "', 
                status          = '" . (int)$data['status'] . "', 
                price           = '" . (float)$data['price'] . "', 
                frequency       = '" . DB::escape($data['frequency']) . "', 
                duration        = '" . (int)$data['duration'] . "', 
                cycle           = '" . (int)$data['cycle'] . "', 
                trial_status    = '" . (int)$data['trial_status'] . "', 
                trial_price     = '" . (float)$data['trial_price'] . "', 
                trial_frequency = '" . DB::escape($data['trial_frequency']) . "', 
                trial_duration  = '" . (int)$data['trial_duration'] . "', 
                trial_cycle     = '" . (int)$data['trial_cycle'] . "'");
        
        $recurring_id = DB::getLastId();
        
        foreach ($data['recurring_description'] as $language_id => $recurring_description) {
             DB::query("
                INSERT INTO " . DB::prefix() . "recurring_description 
                SET 
                    recurring_id = '" . (int)$recurring_id . "', 
                    language_id  = '" . (int)$language_id . "', 
                    name         = '" . DB::escape($recurring_description['name']) . "'
                ");
        }
        
        Theme::trigger('admin_add_recurring', array('recurring_id' => $recurring_id));
        
        return $recurring_id;
    }
    
    public function editRecurring($recurring_id, $data) {
        DB::query("
			UPDATE " . DB::prefix() . "recurring 
			SET 
                price           = '" . (float)$data['price'] . "', 
                frequency       = '" . DB::escape($data['frequency']) . "', 
                duration        = '" . (int)$data['duration'] . "', 
                cycle           = '" . (int)$data['cycle'] . "', 
                sort_order      = '" . (int)$data['sort_order'] . "', 
                status          = '" . (int)$data['status'] . "', 
                trial_price     = '" . (float)$data['trial_price'] . "', 
                trial_frequency = '" . DB::escape($data['trial_frequency']) . "', 
                trial_duration  = '" . (int)$data['trial_duration'] . "', 
                trial_cycle     = '" . (int)$data['trial_cycle'] . "', 
                trial_status    = '" . (int)$data['trial_status'] . "' 
			WHERE recurring_id = '" . (int)$recurring_id . "'");
        
        DB::query("
            DELETE FROM " . DB::prefix() . "recurring_description 
            WHERE recurring_id = '" . (int)$recurring_id . "'");

        foreach ($data['recurring_description'] as $language_id => $recurring_description) {
             DB::query("
                INSERT INTO " . DB::prefix() . "recurring_description 
                SET 
                    recurring_id = '" . (int)$recurring_id . "', 
                    language_id  = '" . (int)$language_id . "', 
                    name         = '" . DB::escape($recurring_description['name']) . "'
                ");
        }
        
        Theme::trigger('admin_edit_recurring', array('recurring_id' => $recurring_id));
    }
    
    public function copyRecurring($recurring_id) {
        $data = $this->getRecurring($recurring_id);
        
        $data['recurring_description'] = $this->getRecurringDescription($recurring_id);
        
        foreach ($data['recurring_description'] as & $recurring_description) {
            $recurring_description['name'].= ' - 2';
        }
        
        $this->addRecurring($data);
    }
    
    public function deleteRecurring($recurring_id) {
        DB::query("
			DELETE FROM " . DB::prefix() . "recurring 
			WHERE recurring_id = '" . (int)$recurring_id . "'");
        
        DB::query("
			DELETE FROM " . DB::prefix() . "recurring_description 
			WHERE recurring_id = '" . (int)$recurring_id . "'");
        
        DB::query("
			DELETE FROM " . DB::prefix() . "product_recurring 
			WHERE recurring_id = '" . (int)$recurring_id . "'");
        
        DB::query("
			UPDATE " . DB::prefix() . "order_recurring 
			SET 
				recurring_id = '0' 
			WHERE recurring_id = '" . (int)$recurring_id . "'");
        
        Theme::trigger('admin_delete_recurring', array('recurring_id' => $recurring_id));
    }
    
    public function getRecurring($recurring_id) {
        $query = DB::query("
			SELECT * 
			FROM " . DB::prefix() . "recurring 
			WHERE recurring_id = '" . (int)$recurring_id . "'");
        
        return $query->row;
    }
    
    public function getRecurringDescription($recurring_id) {
        $recurring_description_data = array();
        
        $query = DB::query("
			SELECT * 
			FROM " . DB::prefix() . "recurring_description 
			WHERE recurring_id = '" . (int)$recurring_id . "'");
        
        foreach ($query->rows as $result) {
            $recurring_description_data[$result['language_id']] = array('name' => $result['name']);
        }
        
        return $recurring_description_data;
    }
    
    public function getRecurrings($data = array()) {
        $sql = "
			SELECT * 
			FROM " . DB::prefix() . "recurring r 
			LEFT JOIN " . DB::prefix() . "recurring_description rd 
			ON (r.recurring_id = rd.recurring_id) 
			WHERE rd.language_id = '" . (int)Config::get('config_language_id') . "'";
        
        if (!empty($data['filter_name'])) {
            $sql.= " AND rd.name LIKE '" . DB::escape($data['filter_name']) . "%'";
        }
        
        $sort_data = array('rd.name', 'r.sort_order');
        
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql.= " ORDER BY {$data['sort']}";
        } else {
            $sql.= " ORDER BY rd.name";
        }
        
        if (isset($data['order']) && ($data['order'] == 'desc')) {
            $sql.= " DESC";
        } else {
            $sql.= " ASC";
        }
        
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            
            $sql.= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        
        $query = DB::query($sql);
        
        return $query->rows;
    }
    
    public function getTotalRecurrings() {
        $query = DB::query("
			SELECT COUNT(*) AS total 
			FROM " . DB::prefix() . "recurring");
        
        return $query->row['total'];
    }
}
