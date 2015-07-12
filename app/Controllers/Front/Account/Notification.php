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

namespace App\Controllers\Front\Account;
use App\Controllers\Controller;

class Notification extends Controller {

	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()):
            $this->session->data['redirect'] = Url::link('account/notification', '', 'SSL');
            $this->response->redirect(Url::link('account/login', '', 'SSL'));
        endif;

		$data = Theme::language('account/notification');

		Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('account/notification');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()):
            $this->model_account_notification->editNotification($this->request->post);
            $this->session->data['success'] = Lang::get('lang_text_success');
        endif;

        Breadcrumb::add('lang_text_account', 'account/dashboard', null, true, 'SSL');
        Breadcrumb::add('lang_heading_title', 'account/notification', null, true, 'SSL');

        if (isset($this->error['warning'])):
            $data['error_warning'] = $this->error['warning'];
        else:
            $data['error_warning'] = '';
        endif;

        if (isset($this->session->data['success'])):
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        else:
            $data['success'] = '';
        endif;

        $customer_notifications = $this->model_account_notification->getCustomerNotifications();
        $emails = $this->model_account_notification->getConfigurableNotifications();

        $data['notifications'] = array();

        foreach($emails as $email):
            $mail     = array();
            $internal = array();

            $mail['title']     = Lang::get('lang_text_email');
            $internal['title'] = Lang::get('lang_text_internal');

            if (array_key_exists($email['email_id'], $customer_notifications)):
                $mail['value']     = $customer_notifications[$email['email_id']]['email'];
                $internal['value'] = $customer_notifications[$email['email_id']]['internal'];
            else:
                $mail['value']     = 0;
                $internal['value'] = 0;
            endif;

            $notify = array(
                'email'    => $mail,
                'internal' => $internal
            );

            $data['notifications'][] = array(
                'id'          => $email['email_id'],
                'slug'        => $email['email_slug'],
                'description' => $email['config_description'],
                'content'     => $notify
            );
        endforeach;

		$data['action'] = Url::link('account/notification', '', 'SSL');
		$data['back']   = Url::link('account/dashboard', '', 'SSL');

        Theme::loadjs('javascript/account/notification', $data);

        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        Theme::set_controller('header', 'shop/header');
        Theme::set_controller('footer', 'shop/footer');
        
        $data = Theme::render_controllers($data);
        
        $this->response->setOutput(Theme::view('account/notification', $data));
	}

    public function inbox() {
        $data = Theme::language('account/notification');
        Theme::model('account/notification');
        
        if (isset($this->request->get['page'])):
            $page = $this->request->get['page'];
        else:
            $page = 1;
        endif;
        
        $data['inbox'] = array();
        
        $total   = $this->model_account_notification->getTotalNotifications();
        $results = $this->model_account_notification->getAllNotifications(($page - 1) * 10, 10);

        $data['back'] = Url::link('account/dashboard', '', 'SSL');
        
        foreach ($results as $result):
           $data['inbox'][] = array(
                'notification_id' => $result['notification_id'],
                'href'            => Url::link('account/notification/read', 'notification_id=' . $result['notification_id'], 'SSL'),
                'subject'         => $result['subject'],
                'read'            => $result['is_read'],
                'delete'          => Url::link('account/notification/delete', 'notification_id=' . $result['notification_id'], 'SSL'),
            ); 
        endforeach;
        
        $data['pagination'] = Theme::paginate(
            $total, 
            $page, 
            10, 
            Lang::get('lang_text_pagination'), 
            Url::link('account/notification/inbox', 'page={page}', 'SSL')
        );
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $this->response->setOutput(Theme::view('account/inbox', $data));
    }

    public function read() {
        Theme::model('account/notification');

        $json = array();

        $id = $this->request->get['notification_id'];

        $json['message'] = html_entity_decode($this->model_account_notification->getInboxNotification($id), ENT_QUOTES, 'UTF-8');

        $json = Theme::listen(__CLASS__, __FUNCTION__, $json);
        
        $this->response->setOutput(json_encode($json));
    }

    public function delete() {
        Theme::language('account/notification');
        Theme::model('account/notification');

        $json = array();

        $notification_id = $this->request->get['notification_id'];

        if ($this->model_account_notification->deleteInboxNotification($notification_id)):
            $json['success'] = Lang::get('lang_text_success');
        endif;

        $json = Theme::listen(__CLASS__, __FUNCTION__, $json);
        
        $this->response->setOutput(json_encode($json));
    }

    public function unsubscribe() {
        if ($this->customer->isLogged()):
            $this->response->redirect(Url::link('account/notification/#tab-settings', '', 'SSL'));
        endif;

        Theme::language('account/notification');
    }

    public function webversion() {
        $data = Theme::language('account/notification');

        $data['webversion'] = false;

        if (isset($this->request->get['id'])):
            Theme::model('account/notification');
            $data['webversion'] = $this->model_account_notification->getWebversion($this->request->get['id']);
        endif;

        $this->response->setOutput(Theme::view('account/webversion', $data));
    }

    public function preferences() {

    }

	private function validate() {
		// just return true as we have nothing to validate here
        return true;
	}
}
