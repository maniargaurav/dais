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

namespace App\Controllers\Admin\Catalog;
use App\Controllers\Controller;

class Filter extends Controller {
    private $error = array();
    
    public function index() {
        Lang::load('catalog/filter');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        Theme::model('catalog/filter');
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getList();
    }
    
    public function insert() {
        Lang::load('catalog/filter');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        Theme::model('catalog/filter');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_filter->addFilter($this->request->post);
            
            $this->session->data['success'] = Lang::get('lang_text_success');
            
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
            
            Response::redirect(Url::link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getForm();
    }
    
    public function update() {
        Lang::load('catalog/filter');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        Theme::model('catalog/filter');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_filter->editFilter($this->request->get['filter_group_id'], $this->request->post);
            
            $this->session->data['success'] = Lang::get('lang_text_success');
            
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
            
            Response::redirect(Url::link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getForm();
    }
    
    public function delete() {
        Lang::load('catalog/filter');
        
        Theme::setTitle(Lang::get('lang_heading_title'));
        
        Theme::model('catalog/filter');
        
        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $filter_group_id) {
                $this->model_catalog_filter->deleteFilter($filter_group_id);
            }
            
            $this->session->data['success'] = Lang::get('lang_text_success');
            
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
            
            Response::redirect(Url::link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getList();
    }
    
    protected function getList() {
        $data = Theme::language('catalog/filter');
        
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'fgd.name';
        }
        
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
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
        
        Breadcrumb::add('lang_heading_title', 'catalog/filter', $url);
        
        $data['insert'] = Url::link('catalog/filter/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = Url::link('catalog/filter/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['filters'] = array();
        
        $filter = array('sort' => $sort, 'order' => $order, 'start' => ($page - 1) * Config::get('config_admin_limit'), 'limit' => Config::get('config_admin_limit'));
        
        $filter_total = $this->model_catalog_filter->getTotalFilterGroups();
        
        $results = $this->model_catalog_filter->getFilterGroups($filter);
        
        foreach ($results as $result) {
            $action = array();
            
            $action[] = array('text' => Lang::get('lang_text_edit'), 'href' => Url::link('catalog/filter/update', 'token=' . $this->session->data['token'] . '&filter_group_id=' . $result['filter_group_id'] . $url, 'SSL'));
            
            $data['filters'][] = array('filter_group_id' => $result['filter_group_id'], 'name' => $result['name'], 'sort_order' => $result['sort_order'], 'selected' => isset($this->request->post['selected']) && in_array($result['filter_group_id'], $this->request->post['selected']), 'action' => $action);
        }
        
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
        
        $data['sort_name'] = Url::link('catalog/filter', 'token=' . $this->session->data['token'] . '&sort=fgd.name' . $url, 'SSL');
        $data['sort_sort_order'] = Url::link('catalog/filter', 'token=' . $this->session->data['token'] . '&sort=fg.sort_order' . $url, 'SSL');
        
        $url = '';
        
        if (isset($this->request->get['sort'])) {
            $url.= '&sort=' . $this->request->get['sort'];
        }
        
        if (isset($this->request->get['order'])) {
            $url.= '&order=' . $this->request->get['order'];
        }
        
        $data['pagination'] = Theme::paginate($filter_total, $page, Config::get('config_admin_limit'), Lang::get('lang_text_pagination'), Url::link('catalog/filter', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL'));
        
        $data['sort'] = $sort;
        $data['order'] = $order;
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::render_controllers($data);
        
        Response::setOutput(Theme::view('catalog/filter_list', $data));
    }
    
    protected function getForm() {
        $data = Theme::language('catalog/filter');
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['group'])) {
            $data['error_group'] = $this->error['group'];
        } else {
            $data['error_group'] = array();
        }
        
        if (isset($this->error['filter'])) {
            $data['error_filter'] = $this->error['filter'];
        } else {
            $data['error_filter'] = array();
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
        
        Breadcrumb::add('lang_heading_title', 'catalog/filter', $url);
        
        if (!isset($this->request->get['filter_group_id'])) {
            $data['action'] = Url::link('catalog/filter/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = Url::link('catalog/filter/update', 'token=' . $this->session->data['token'] . '&filter_group_id=' . $this->request->get['filter_group_id'] . $url, 'SSL');
        }
        
        $data['cancel'] = Url::link('catalog/filter', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        if (isset($this->request->get['filter_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $filter_group_info = $this->model_catalog_filter->getFilterGroup($this->request->get['filter_group_id']);
        }
        
        $data['token'] = $this->session->data['token'];
        
        Theme::model('localization/language');
        
        $data['languages'] = $this->model_localization_language->getLanguages();
        
        if (isset($this->request->post['filter_group_description'])) {
            $data['filter_group_description'] = $this->request->post['filter_group_description'];
        } elseif (isset($this->request->get['filter_group_id'])) {
            $data['filter_group_description'] = $this->model_catalog_filter->getFilterGroupDescriptions($this->request->get['filter_group_id']);
        } else {
            $data['filter_group_description'] = array();
        }
        
        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($filter_group_info)) {
            $data['sort_order'] = $filter_group_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }
        
        if (isset($this->request->post['filters'])) {
            $data['filters'] = $this->request->post['filter'];
        } elseif (isset($this->request->get['filter_group_id'])) {
            $data['filters'] = $this->model_catalog_filter->getFilterDescriptions($this->request->get['filter_group_id']);
        } else {
            $data['filters'] = array();
        }
        
        Theme::loadjs('javascript/catalog/filter_form', $data);
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::render_controllers($data);
        
        Response::setOutput(Theme::view('catalog/filter_form', $data));
    }
    
    protected function validateForm() {
        if (!User::hasPermission('modify', 'catalog/filter')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        foreach ($this->request->post['filter_group_description'] as $language_id => $value) {
            if ((Encode::strlen($value['name']) < 1) || (Encode::strlen($value['name']) > 64)) {
                $this->error['group'][$language_id] = Lang::get('lang_error_group');
            }
        }
        
        if (isset($this->request->post['filter'])) {
            foreach ($this->request->post['filter'] as $filter_id => $filter) {
                foreach ($filter['filter_description'] as $language_id => $filter_description) {
                    if ((Encode::strlen($filter_description['name']) < 1) || (Encode::strlen($filter_description['name']) > 64)) {
                        $this->error['filter'][$filter_id][$language_id] = Lang::get('lang_error_name');
                    }
                }
            }
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
    
    protected function validateDelete() {
        if (!User::hasPermission('modify', 'catalog/filter')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
    
    public function autocomplete() {
        $json = array();
        
        if (isset($this->request->get['filter_name'])) {
            Theme::model('catalog/filter');
            
            $filter = array('filter_name' => $this->request->get['filter_name'], 'start' => 0, 'limit' => 20);
            
            $filters = $this->model_catalog_filter->getFilters($filter);
            
            foreach ($filters as $filter) {
                $json[] = array('filter_id' => $filter['filter_id'], 'name' => strip_tags(html_entity_decode($filter['group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8')));
            }
        }
        
        $sort_order = array();
        
        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
        
        array_multisort($sort_order, SORT_ASC, $json);
        
        $json = Theme::listen(__CLASS__, __FUNCTION__, $json);
        
        Response::setOutput(json_encode($json));
    }
}
