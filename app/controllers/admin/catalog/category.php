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

class Category extends Controller {
    
    private $error = array();
    
    public function index() {
        Lang::load('catalog/category');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('catalog/category');
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getList();
    }
    
    public function insert() {
        Lang::load('catalog/category');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('catalog/category');
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            CatalogCategory::addCategory(Request::post());
            Session::p()->data['success'] = Lang::get('lang_text_success');
            
            $url = '';
            
            if (isset(Request::p()->get['page'])) {
                $url.= '&page=' . Request::p()->get['page'];
            }
            
            Response::redirect(Url::link('catalog/category', '' . $url, 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getForm();
    }
    
    public function update() {
        Lang::load('catalog/category');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('catalog/category');
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            CatalogCategory::editCategory(Request::p()->get['category_id'], Request::post());
            Session::p()->data['success'] = Lang::get('lang_text_success');
            
            $url = '';
            
            if (isset(Request::p()->get['page'])) {
                $url.= '&page=' . Request::p()->get['page'];
            }
            
            Response::redirect(Url::link('catalog/category', '' . $url, 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getForm();
    }
    
    public function delete() {
        Lang::load('catalog/category');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('catalog/category');
        
        if (isset(Request::p()->post['selected']) && $this->validateDelete()) {
            foreach (Request::p()->post['selected'] as $category_id) {
                CatalogCategory::deleteCategory($category_id);
            }
            
            Session::p()->data['success'] = Lang::get('lang_text_success');
            
            $url = '';
            
            if (isset(Request::p()->get['page'])) {
                $url.= '&page=' . Request::p()->get['page'];
            }
            
            Response::redirect(Url::link('catalog/category', '' . $url, 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getList();
    }
    
    public function repair() {
        Lang::load('catalog/category');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('catalog/category');
        
        if ($this->validateRepair()) {
            CatalogCategory::repairCategories();
            Session::p()->data['success'] = Lang::get('lang_text_success');
            
            Response::redirect(Url::link('catalog/category', '', 'SSL'));
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        $this->getList();
    }
    
    protected function getList() {
        $data = Theme::language('catalog/category');
        
        if (isset(Request::p()->get['page'])) {
            $page = Request::p()->get['page'];
        } else {
            $page = 1;
        }
        
        $url = '';
        
        if (isset(Request::p()->get['page'])) {
            $url.= '&page=' . Request::p()->get['page'];
        }
        
        Breadcrumb::add('lang_heading_title', 'catalog/category', $url);
        
        $data['insert'] = Url::link('catalog/category/insert', '' . $url, 'SSL');
        $data['delete'] = Url::link('catalog/category/delete', '' . $url, 'SSL');
        $data['repair'] = Url::link('catalog/category/repair', '' . $url, 'SSL');
        
        $data['categories'] = array();
        
        $filter = array('start' => ($page - 1) * Config::get('config_admin_limit'), 'limit' => Config::get('config_admin_limit'));
        
        $category_total = CatalogCategory::getTotalCategories();
        
        $results = CatalogCategory::getCategories($filter);
        
        foreach ($results as $result) {
            $action = array();
            
            $action[] = array('text' => Lang::get('lang_text_edit'), 'href' => Url::link('catalog/category/update', '' . 'category_id=' . $result['category_id'] . $url, 'SSL'));
            
            $data['categories'][] = array('category_id' => $result['category_id'], 'name' => $result['name'], 'sort_order' => $result['sort_order'], 'selected' => isset(Request::p()->post['selected']) && in_array($result['category_id'], Request::p()->post['selected']), 'action' => $action);
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset(Session::p()->data['success'])) {
            $data['success'] = Session::p()->data['success'];
            
            unset(Session::p()->data['success']);
        } else {
            $data['success'] = '';
        }
        
        $data['pagination'] = Theme::paginate($category_total, $page, Config::get('config_admin_limit'), Lang::get('lang_text_pagination'), Url::link('catalog/category', '' . $url . '&page={page}', 'SSL'));
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('catalog/category_list', $data));
    }
    
    protected function getForm() {
        $data = Theme::language('catalog/category');
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }
        
        if (isset($this->error['slug'])) {
            $data['error_slug'] = $this->error['slug'];
        } else {
            $data['error_slug'] = '';
        }
        
        $url = '';
        
        if (isset(Request::p()->get['page'])) {
            $url.= '&page=' . Request::p()->get['page'];
        }
        
        Breadcrumb::add('lang_heading_title', 'catalog/category', $url);
        
        if (!isset(Request::p()->get['category_id'])) {
            $data['action'] = Url::link('catalog/category/insert', '' . $url, 'SSL');
        } else {
            $data['action'] = Url::link('catalog/category/update', '' . 'category_id=' . Request::p()->get['category_id'] . $url, 'SSL');
        }
        
        $data['cancel'] = Url::link('catalog/category', '' . $url, 'SSL');
        
        if (isset(Request::p()->get['category_id']) && (Request::p()->server['REQUEST_METHOD'] != 'POST')) {
            $category_info = CatalogCategory::getCategory(Request::p()->get['category_id']);
        }
        
        Theme::model('locale/language');
        
        $data['languages'] = LocaleLanguage::getLanguages();
        
        if (isset(Request::p()->post['category_description'])) {
            $data['category_description'] = Request::p()->post['category_description'];
        } elseif (isset(Request::p()->get['category_id'])) {
            $data['category_description'] = CatalogCategory::getCategoryDescriptions(Request::p()->get['category_id']);
        } else {
            $data['category_description'] = array();
        }
        
        if (isset(Request::p()->post['path'])) {
            $data['path'] = Request::p()->post['path'];
        } elseif (!empty($category_info)) {
            $data['path'] = $category_info['path'];
        } else {
            $data['path'] = '';
        }
        
        if (isset(Request::p()->post['parent_id'])) {
            $data['parent_id'] = Request::p()->post['parent_id'];
        } elseif (!empty($category_info)) {
            $data['parent_id'] = $category_info['parent_id'];
        } else {
            $data['parent_id'] = 0;
        }
        
        Theme::model('catalog/filter');
        
        if (isset(Request::p()->post['category_filter'])) {
            $filters = Request::p()->post['category_filter'];
        } elseif (isset(Request::p()->get['category_id'])) {
            $filters = CatalogCategory::getCategoryFilters(Request::p()->get['category_id']);
        } else {
            $filters = array();
        }
        
        $data['category_filters'] = array();
        
        foreach ($filters as $filter_id) {
            $filter_info = CatalogFilter::getFilter($filter_id);
            
            if ($filter_info) {
                $data['category_filters'][] = array('filter_id' => $filter_info['filter_id'], 'name' => $filter_info['group'] . ' &gt; ' . $filter_info['name']);
            }
        }
        
        Theme::model('setting/store');
        
        $data['stores'] = SettingStore::getStores();
        
        if (isset(Request::p()->post['category_store'])) {
            $data['category_store'] = Request::p()->post['category_store'];
        } elseif (isset(Request::p()->get['category_id'])) {
            $data['category_store'] = CatalogCategory::getCategoryStores(Request::p()->get['category_id']);
        } else {
            $data['category_store'] = array(0);
        }
        
        if (isset(Request::p()->post['slug'])) {
            $data['slug'] = Request::p()->post['slug'];
        } elseif (!empty($category_info)) {
            $data['slug'] = $category_info['slug'];
        } else {
            $data['slug'] = '';
        }
        
        if (isset(Request::p()->post['image'])) {
            $data['image'] = Request::p()->post['image'];
        } elseif (!empty($category_info)) {
            $data['image'] = $category_info['image'];
        } else {
            $data['image'] = '';
        }
        
        Theme::model('tool/image');
        
        if (isset(Request::p()->post['image']) && file_exists(Config::get('path.image') . Request::p()->post['image'])) {
            $data['thumb'] = ToolImage::resize(Request::p()->post['image'], 100, 100);
        } elseif (!empty($category_info) && $category_info['image'] && file_exists(Config::get('path.image') . $category_info['image'])) {
            $data['thumb'] = ToolImage::resize($category_info['image'], 100, 100);
        } else {
            $data['thumb'] = ToolImage::resize('placeholder.png', 100, 100);
        }
        
        $data['no_image'] = ToolImage::resize('placeholder.png', 100, 100);
        
        if (isset(Request::p()->post['top'])) {
            $data['top'] = Request::p()->post['top'];
        } elseif (!empty($category_info)) {
            $data['top'] = $category_info['top'];
        } else {
            $data['top'] = 0;
        }
        
        if (isset(Request::p()->post['columns'])) {
            $data['columns'] = Request::p()->post['columns'];
        } elseif (!empty($category_info)) {
            $data['columns'] = $category_info['columns'];
        } else {
            $data['columns'] = 1;
        }
        
        if (isset(Request::p()->post['sort_order'])) {
            $data['sort_order'] = Request::p()->post['sort_order'];
        } elseif (!empty($category_info)) {
            $data['sort_order'] = $category_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }
        
        if (isset(Request::p()->post['status'])) {
            $data['status'] = Request::p()->post['status'];
        } elseif (!empty($category_info)) {
            $data['status'] = $category_info['status'];
        } else {
            $data['status'] = 1;
        }
        
        if (isset(Request::p()->post['category_layout'])) {
            $data['category_layout'] = Request::p()->post['category_layout'];
        } elseif (isset(Request::p()->get['category_id'])) {
            $data['category_layout'] = CatalogCategory::getCategoryLayouts(Request::p()->get['category_id']);
        } else {
            $data['category_layout'] = array();
        }
        
        Theme::model('design/layout');
        
        $data['layouts'] = DesignLayout::getLayouts();

        Theme::loadjs('javascript/catalog/category_form', $data);
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('catalog/category_form', $data));
    }
    
    protected function validateForm() {
        if (!User::hasPermission('modify', 'catalog/category')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        foreach (Request::p()->post['category_description'] as $language_id => $value) {
            if ((Encode::strlen($value['name']) < 2) || (Encode::strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = Lang::get('lang_error_name');
            }
        }
        
        if (isset(Request::p()->post['slug']) && Encode::strlen(Request::p()->post['slug']) > 0):
            Theme::model('tool/utility');
            $query = ToolUtility::findSlugByName(Request::p()->post['slug']);
            
            if (isset(Request::p()->get['category_id'])):
                if ($query):
                    if ($query != 'category_id:' . Request::p()->get['category_id']):
                        $this->error['slug'] = sprintf(Lang::get('lang_error_slug_found'), Request::p()->post['slug']);
                    endif;
                endif;
            else:
                if ($query):
                    $this->error['slug'] = sprintf(Lang::get('lang_error_slug_found'), Request::p()->post['slug']);
                endif;
            endif;
        else:
            $this->error['slug'] = Lang::get('lang_error_slug');
        endif;
        
        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = Lang::get('lang_error_warning');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
    
    protected function validateDelete() {
        if (!User::hasPermission('modify', 'catalog/category')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
    
    protected function validateRepair() {
        if (!User::hasPermission('modify', 'catalog/category')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
    
    public function autocomplete() {
        $json = array();
        
        if (isset(Request::p()->get['filter_name'])) {
            Theme::model('catalog/category');
            
            $filter = array('filter_name' => Request::p()->get['filter_name'], 'start' => 0, 'limit' => 20);
            
            $results = CatalogCategory::getCategories($filter);
            
            foreach ($results as $result) {
                $json[] = array('category_id' => $result['category_id'], 'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')));
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
    
    public function slug() {
        Lang::load('catalog/category');
        Theme::model('tool/utility');
        
        $json = array();
        
        if (!isset(Request::p()->get['name']) || Encode::strlen(Request::p()->get['name']) < 1):
            $json['error'] = Lang::get('lang_error_name_first');
        else:
            
            // build slug
            $slug = Naming::build_slug(Request::p()->get['name']);
            
            // check that the slug is globally unique
            $query = ToolUtility::findSlugByName($slug);
            
            if ($query):
                if (isset(Request::p()->get['category_id'])):
                    if ($query != 'category_id:' . Request::p()->get['category_id']):
                        $json['error'] = sprintf(Lang::get('lang_error_slug_found'), $slug);
                    else:
                        $json['slug'] = $slug;
                    endif;
                else:
                    $json['error'] = sprintf(Lang::get('lang_error_slug_found'), $slug);
                endif;
            else:
                $json['slug'] = $slug;
            endif;
        endif;
        
        $json = Theme::listen(__CLASS__, __FUNCTION__, $json);
        
        Response::setOutput(json_encode($json));
    }

    public function description() {
        $json = array();

        if (isset(Request::p()->post['description']))
            $json['success'] = $this->keyword->getDescription(Request::p()->post['description']);

        Response::setOutput(json_encode($json));
    }

    public function keyword() {
        $json = array();

        if (isset(Request::p()->post['keywords'])):
            // let's clean up the data first
            $keywords        = $this->keyword->getDescription(Request::p()->post['keywords']);
            $json['success'] = $this->keyword->getKeywords($keywords);
        endif;

        Response::setOutput(json_encode($json));
    }
}
