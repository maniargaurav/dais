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

namespace App\Models\Front\Account;
use App\Models\Model;

class Notification extends Model {
	public function getConfigurableNotifications() {
		$query = DB::query("
			SELECT email_id, email_slug, config_description 
			FROM " . DB::prefix() . "email 
			WHERE recipient = '1' 
			AND configurable = '1'");

		return $query->rows;
	}

	public function getCustomerNotifications() {
		$query = DB::query("
			SELECT settings 
			FROM " . DB::prefix() . "customer_notification 
			WHERE customer_id = '" . (int)\Customer::getId() . "'");

		$data = unserialize($query->row['settings']);

		return $data;
	}

	public function editNotification($data) {
		// Sadly it seems we'll need a major work-around here
		// because unchecked checkboxes don't get posted.
		
		$emails = $this->getConfigurableNotifications();

		foreach($emails as $email):
			if (!array_key_exists($email['email_id'], $data['notification'])):
				$data['notification'][$email['email_id']] = array(
					'email'    => 0,
					'internal' => 0
				);
			else:
				// Here we need to check for both notify types within each array
				// and set them to zero if they didn't get posted
				foreach($data['notification'] as $key => $content):
					if (!isset($content['email'])):
						$data['notification'][$key]['email'] = 0;
					endif;
					if (!isset($content['internal'])):
						$data['notification'][$key]['internal'] = 0;
					endif;
				endforeach;
			endif;
		endforeach;

		$notify = array();
		
		foreach($data['notification'] as $key => $content):
			foreach ($content as $k => $v):
				$item[$k] = $v;
			endforeach;
			$notify[$key] = $item;
		endforeach;

		$notify = serialize($notify);

		DB::query("
			UPDATE " . DB::prefix() . "customer_notification 
			SET 
				settings = '" . DB::escape($notify) . "' 
			WHERE customer_id = '" . (int)\Customer::getId() . "'");

		return true;
	}

	public function getTotalNotifications() {
		$query = DB::query("
			SELECT COUNT(notification_id) AS total 
			FROM " . DB::prefix() . "customer_inbox 
			WHERE customer_id = '" . (int)\Customer::getId() . "'
		");

		return $query->row['total'];
	}

	public function getAllNotifications($start = 0, $limit = 10) {
		if ($start < 0) $start = 0;
        if ($limit < 1) $limit = 10;

		$query = DB::query("
			SELECT * 
			FROM " . DB::prefix() . "customer_inbox 
			WHERE customer_id = '" . (int)\Customer::getId() . "' 
			ORDER BY notification_id DESC 
			LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getInboxNotification($notification_id) {
		$query = DB::query("
			SELECT message 
			FROM " . DB::prefix() . "customer_inbox 
			WHERE notification_id = '" . (int)$notification_id . "' 
			AND customer_id = '" . (int)\Customer::getId() . "'");

		// this is the process for reading so let's mark it as read
		$this->markRead($notification_id);

		return $query->row['message'];
	}

	public function markRead($notification_id) {
		DB::query("
			UPDATE " . DB::prefix() . "customer_inbox 
			SET is_read = '1' 
			WHERE notification_id = '" . (int)$notification_id . "' 
			AND customer_id = '" . (int)\Customer::getId() . "'");
	}

	public function deleteInboxNotification($notification_id) {
		$query = DB::query("
			DELETE 
			FROM " . DB::prefix() . "customer_inbox 
			WHERE notification_id = '" . (int)$notification_id . "' 
			AND customer_id = '" . (int)\Customer::getId() . "'");

		if ($query) return true;
	}

	public function getWebversion($id) {
		$query = DB::query("
			SELECT html 
			FROM " . DB::prefix() . "notification_queue 
			WHERE queue_id = '" . (int)$id . "'
		");

		if ($query->num_rows):
			return $query->row['html'];
		else:
			return false;
		endif;
	}
}
