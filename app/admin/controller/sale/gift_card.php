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

namespace Admin\Controller\Sale;
use Dais\Engine\Controller;

class GiftCard extends Controller {
    private $error = array();
    
    public function index() {
        $this->language->load('sale/gift_card');
        $this->theme->setTitle($this->language->get('lang_heading_title'));
        $this->theme->model('sale/gift_card');
        
        $this->theme->listen(__CLASS__, __FUNCTION__);
        
        $this->getList();
    }
    
    public function insert() {
        $this->language->load('sale/gift_card');
        $this->theme->setTitle($this->language->get('lang_heading_title'));
        $this->theme->model('sale/gift_card');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_sale_gift_card->addGiftcard($this->request->post);
            $this->session->data['success'] = $this->language->get('lang_text_success');
            
            $url = '';
            
            if (isset($this->request->get['sort'])) {
                $url.= '&sort=' . $this->request->get['sort'];
            }
            
            if (isset($this->request->get['order'])) {
                $url.= '&order=' . $this->request->get['order'];
            }
            
            if (isset($this->request->get['page'])) {
                $url.= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        
        $this->theme->listen(__CLASS__, __FUNCTION__);
        
        $this->getForm();
    }
    
    public function update() {
        $this->language->load('sale/gift_card');
        $this->theme->setTitle($this->language->get('lang_heading_title'));
        $this->theme->model('sale/gift_card');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_sale_gift_card->editGiftcard($this->request->get['gift_card_id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('lang_text_success');
            
            $url = '';
            
            if (isset($this->request->get['sort'])) {
                $url.= '&sort=' . $this->request->get['sort'];
            }
            
            if (isset($this->request->get['order'])) {
                $url.= '&order=' . $this->request->get['order'];
            }
            
            if (isset($this->request->get['page'])) {
                $url.= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        
        $this->theme->listen(__CLASS__, __FUNCTION__);
        
        $this->getForm();
    }
    
    public function delete() {
        $this->language->load('sale/gift_card');
        $this->theme->setTitle($this->language->get('lang_heading_title'));
        $this->theme->model('sale/gift_card');
        
        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $gift_card_id) {
                $this->model_sale_gift_card->deleteGiftcard($gift_card_id);
            }
            
            $this->session->data['success'] = $this->language->get('lang_text_success');
            
            $url = '';
            
            if (isset($this->request->get['sort'])) {
                $url.= '&sort=' . $this->request->get['sort'];
            }
            
            if (isset($this->request->get['order'])) {
                $url.= '&order=' . $this->request->get['order'];
            }
            
            if (isset($this->request->get['page'])) {
                $url.= '&page=' . $this->request->get['page'];
            }
            
            $this->response->redirect($this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        
        $this->theme->listen(__CLASS__, __FUNCTION__);
        
        $this->getList();
    }
    
    protected function getList() {
        $data = $this->theme->language('sale/gift_card');
        
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'v.date_added';
        }
        
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        $url = '';
        
        if (isset($this->request->get['sort'])) {
            $url.= '&sort=' . $this->request->get['sort'];
        }
        
        if (isset($this->request->get['order'])) {
            $url.= '&order=' . $this->request->get['order'];
        }
        
        if (isset($this->request->get['page'])) {
            $url.= '&page=' . $this->request->get['page'];
        }
        
        $this->breadcrumb->add('lang_heading_title', 'sale/gift_card', $url);
        
        $data['insert'] = $this->url->link('sale/gift_card/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('sale/gift_card/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['gift_cards'] = array();
        
        $filter = array(
            'sort'  => $sort, 
            'order' => $order, 
            'start' => ($page - 1) * $this->config->get('config_admin_limit'), 
            'limit' => $this->config->get('config_admin_limit')
        );
        
        $gift_card_total = $this->model_sale_gift_card->getTotalGiftcards();
        
        $results = $this->model_sale_gift_card->getGiftcards($filter);
        
        foreach ($results as $result) {
            $action = array();
            
            $action[] = array(
                'text' => $this->language->get('lang_text_edit'), 
                'href' => $this->url->link('sale/gift_card/update', 'token=' . $this->session->data['token'] . '&gift_card_id=' . $result['gift_card_id'] . $url, 'SSL')
            );
            
            $data['gift_cards'][] = array(
                'gift_card_id' => $result['gift_card_id'], 
                'code'        => $result['code'], 
                'from'        => $result['from_name'], 
                'to'          => $result['to_name'], 
                'theme'       => $result['theme'], 
                'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')), 
                'status'      => ($result['status'] ? $this->language->get('lang_text_enabled') : $this->language->get('lang_text_disabled')), 
                'date_added'  => date($this->language->get('lang_date_format_short'), strtotime($result['date_added'])), 
                'selected'    => isset($this->request->post['selected']) && in_array($result['gift_card_id'], $this->request->post['selected']), 
                'action'      => $action
            );
        }
        
        $data['token'] = $this->session->data['token'];
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        
        $url = '';
        
        if ($order == 'ASC') {
            $url.= '&order=DESC';
        } else {
            $url.= '&order=ASC';
        }
        
        if (isset($this->request->get['page'])) {
            $url.= '&page=' . $this->request->get['page'];
        }
        
        $data['sort_code']       = $this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . '&sort=v.code' . $url, 'SSL');
        $data['sort_from']       = $this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . '&sort=v.from_name' . $url, 'SSL');
        $data['sort_to']         = $this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . '&sort=v.to_name' . $url, 'SSL');
        $data['sort_theme']      = $this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . '&sort=theme' . $url, 'SSL');
        $data['sort_amount']     = $this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . '&sort=v.amount' . $url, 'SSL');
        $data['sort_status']     = $this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . '&sort=v.date_end' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . '&sort=v.date_added' . $url, 'SSL');
        
        $url = '';
        
        if (isset($this->request->get['sort'])) {
            $url.= '&sort=' . $this->request->get['sort'];
        }
        
        if (isset($this->request->get['order'])) {
            $url.= '&order=' . $this->request->get['order'];
        }
        
        $data['pagination'] = $this->theme->paginate(
            $gift_card_total, 
            $page, 
            $this->config->get('config_admin_limit'), 
            $this->language->get('lang_text_pagination'), 
            $this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL')
        );
        
        $data['sort']  = $sort;
        $data['order'] = $order;
        
        $data = $this->theme->listen(__CLASS__, __FUNCTION__, $data);
        
        $data = $this->theme->render_controllers($data);
        
        $this->response->setOutput($this->theme->view('sale/gift_card_list', $data));
    }
    
    protected function getForm() {
        $data = $this->theme->language('sale/gift_card');
        
        if (isset($this->request->get['gift_card_id'])) {
            $data['gift_card_id'] = $this->request->get['gift_card_id'];
        } else {
            $data['gift_card_id'] = 0;
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }
        
        if (isset($this->error['from_name'])) {
            $data['error_from_name'] = $this->error['from_name'];
        } else {
            $data['error_from_name'] = '';
        }
        
        if (isset($this->error['from_email'])) {
            $data['error_from_email'] = $this->error['from_email'];
        } else {
            $data['error_from_email'] = '';
        }
        
        if (isset($this->error['to_name'])) {
            $data['error_to_name'] = $this->error['to_name'];
        } else {
            $data['error_to_name'] = '';
        }
        
        if (isset($this->error['to_email'])) {
            $data['error_to_email'] = $this->error['to_email'];
        } else {
            $data['error_to_email'] = '';
        }
        
        if (isset($this->error['amount'])) {
            $data['error_amount'] = $this->error['amount'];
        } else {
            $data['error_amount'] = '';
        }
        
        $url = '';
        
        if (isset($this->request->get['sort'])) {
            $url.= '&sort=' . $this->request->get['sort'];
        }
        
        if (isset($this->request->get['order'])) {
            $url.= '&order=' . $this->request->get['order'];
        }
        
        if (isset($this->request->get['page'])) {
            $url.= '&page=' . $this->request->get['page'];
        }
        
        $this->breadcrumb->add('lang_heading_title', 'sale/gift_card', $url);
        
        if (!isset($this->request->get['gift_card_id'])) {
            $data['action'] = $this->url->link('sale/gift_card/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('sale/gift_card/update', 'token=' . $this->session->data['token'] . '&gift_card_id=' . $this->request->get['gift_card_id'] . $url, 'SSL');
        }
        
        $data['cancel'] = $this->url->link('sale/gift_card', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        if (isset($this->request->get['gift_card_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
            $gift_card_info = $this->model_sale_gift_card->getGiftcard($this->request->get['gift_card_id']);
        }
        
        $data['token'] = $this->session->data['token'];
        
        if (isset($this->request->post['code'])) {
            $data['code'] = $this->request->post['code'];
        } elseif (!empty($gift_card_info)) {
            $data['code'] = $gift_card_info['code'];
        } else {
            $data['code'] = '';
        }
        
        if (isset($this->request->post['from_name'])) {
            $data['from_name'] = $this->request->post['from_name'];
        } elseif (!empty($gift_card_info)) {
            $data['from_name'] = $gift_card_info['from_name'];
        } else {
            $data['from_name'] = '';
        }
        
        if (isset($this->request->post['from_email'])) {
            $data['from_email'] = $this->request->post['from_email'];
        } elseif (!empty($gift_card_info)) {
            $data['from_email'] = $gift_card_info['from_email'];
        } else {
            $data['from_email'] = '';
        }
        
        if (isset($this->request->post['to_name'])) {
            $data['to_name'] = $this->request->post['to_name'];
        } elseif (!empty($gift_card_info)) {
            $data['to_name'] = $gift_card_info['to_name'];
        } else {
            $data['to_name'] = '';
        }
        
        if (isset($this->request->post['to_email'])) {
            $data['to_email'] = $this->request->post['to_email'];
        } elseif (!empty($gift_card_info)) {
            $data['to_email'] = $gift_card_info['to_email'];
        } else {
            $data['to_email'] = '';
        }
        
        $this->theme->model('sale/gift_card_theme');
        
        $data['gift_card_themes'] = $this->model_sale_gift_card_theme->getGiftcardThemes();
        
        if (isset($this->request->post['gift_card_theme_id'])) {
            $data['gift_card_theme_id'] = $this->request->post['gift_card_theme_id'];
        } elseif (!empty($gift_card_info)) {
            $data['gift_card_theme_id'] = $gift_card_info['gift_card_theme_id'];
        } else {
            $data['gift_card_theme_id'] = '';
        }
        
        if (isset($this->request->post['message'])) {
            $data['message'] = $this->request->post['message'];
        } elseif (!empty($gift_card_info)) {
            $data['message'] = $gift_card_info['message'];
        } else {
            $data['message'] = '';
        }
        
        if (isset($this->request->post['amount'])) {
            $data['amount'] = $this->request->post['amount'];
        } elseif (!empty($gift_card_info)) {
            $data['amount'] = $gift_card_info['amount'];
        } else {
            $data['amount'] = '';
        }
        
        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($gift_card_info)) {
            $data['status'] = $gift_card_info['status'];
        } else {
            $data['status'] = 1;
        }
        
        $data = $this->theme->listen(__CLASS__, __FUNCTION__, $data);
        
        $data = $this->theme->render_controllers($data);
        
        $this->response->setOutput($this->theme->view('sale/gift_card_form', $data));
    }
    
    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'sale/gift_card')) {
            $this->error['warning'] = $this->language->get('lang_error_permission');
        }
        
        if (($this->encode->strlen($this->request->post['code']) < 3) || ($this->encode->strlen($this->request->post['code']) > 10)) {
            $this->error['code'] = $this->language->get('lang_error_code');
        }
        
        $gift_card_info = $this->model_sale_gift_card->getGiftcardByCode($this->request->post['code']);
        
        if ($gift_card_info) {
            if (!isset($this->request->get['gift_card_id'])) {
                $this->error['warning'] = $this->language->get('lang_error_exists');
            } elseif ($gift_card_info['gift_card_id'] != $this->request->get['gift_card_id']) {
                $this->error['warning'] = $this->language->get('lang_error_exists');
            }
        }
        
        if (($this->encode->strlen($this->request->post['to_name']) < 1) || ($this->encode->strlen($this->request->post['to_name']) > 64)) {
            $this->error['to_name'] = $this->language->get('lang_error_to_name');
        }
        
        if (($this->encode->strlen($this->request->post['to_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['to_email'])) {
            $this->error['to_email'] = $this->language->get('lang_error_email');
        }
        
        if (($this->encode->strlen($this->request->post['from_name']) < 1) || ($this->encode->strlen($this->request->post['from_name']) > 64)) {
            $this->error['from_name'] = $this->language->get('lang_error_from_name');
        }
        
        if (($this->encode->strlen($this->request->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['from_email'])) {
            $this->error['from_email'] = $this->language->get('lang_error_email');
        }
        
        if ($this->request->post['amount'] < 1) {
            $this->error['amount'] = $this->language->get('lang_error_amount');
        }
        
        $this->theme->listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
    
    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'sale/gift_card')) {
            $this->error['warning'] = $this->language->get('lang_error_permission');
        }
        
        $this->theme->model('sale/order');
        
        foreach ($this->request->post['selected'] as $gift_card_id) {
            $order_gift_card_info = $this->model_sale_order->getOrderGiftcardByGiftcardId($gift_card_id);
            
            if ($order_gift_card_info) {
                $this->error['warning'] = sprintf($this->language->get('lang_error_order'), $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order_gift_card_info['order_id'], 'SSL'));
                
                break;
            }
        }
        
        $this->theme->listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
    
    public function history() {
        $data = $this->theme->language('sale/gift_card');
        
        $this->theme->model('sale/gift_card');
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        $data['histories'] = array();
        
        $results = $this->model_sale_gift_card->getGiftcardHistories($this->request->get['gift_card_id'], ($page - 1) * 10, 10);
        
        foreach ($results as $result) {
            $data['histories'][] = array(
                'order_id'   => $result['order_id'], 
                'customer'   => $result['customer'], 
                'amount'     => $this->currency->format($result['amount'], $this->config->get('config_currency')), 
                'date_added' => date($this->language->get('lang_date_format_short'), strtotime($result['date_added']))
            );
        }
        
        $history_total = $this->model_sale_gift_card->getTotalGiftcardHistories($this->request->get['gift_card_id']);
        
        $data['pagination'] = $this->theme->paginate(
            $history_total, 
            $page, 
            10, 
            $this->language->get('lang_text_pagination'), 
            $this->url->link('sale/gift_card/history', 'token=' . $this->session->data['token'] . '&gift_card_id=' . $this->request->get['gift_card_id'] . '&page={page}', 'SSL')
        );
        
        $data = $this->theme->listen(__CLASS__, __FUNCTION__, $data);
        
        $this->response->setOutput($this->theme->view('sale/gift_card_history', $data));
    }
    
    public function send() {
        $this->language->load('sale/gift_card');
        
        $json = array();
        
        if (!$this->user->hasPermission('modify', 'sale/gift_card')) {
            $json['error'] = $this->language->get('lang_error_permission');
        } elseif (isset($this->request->get['gift_card_id'])) {
            $this->theme->model('sale/gift_card');
            
            $this->model_sale_gift_card->sendGiftcard($this->request->get['gift_card_id']);
            
            $json['success'] = $this->language->get('lang_text_sent');
        }
        
        $json = $this->theme->listen(__CLASS__, __FUNCTION__, $json);
        
        $this->response->setOutput(json_encode($json));
    }
}