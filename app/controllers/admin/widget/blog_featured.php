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

namespace App\Controllers\Admin\Widget;

use App\Controllers\Controller;

class BlogFeatured extends Controller {
    
    private $error = array();
    
    public function index() {
        $data = Theme::language('widget/blog_featured');
        Theme::setTitle(Lang::get('lang_heading_title'));
        Theme::model('setting/setting');
        
        if ((Request::p()->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            SettingSetting::editSetting('blog_featured', Request::post());
            Session::p()->data['success'] = Lang::get('lang_text_success');
            
            Response::redirect(Url::link('module/widget', '', 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['image'])) {
            $data['error_image'] = $this->error['image'];
        } else {
            $data['error_image'] = array();
        }
        
        Breadcrumb::add('lang_text_widget', 'module/widget');
        Breadcrumb::add('lang_heading_title', 'widget/blog_featured');
        
        $data['action'] = Url::link('widget/blog_featured', '', 'SSL');
        $data['cancel'] = Url::link('module/widget', '', 'SSL');
        
        if (isset(Request::p()->post['blog_featured_post'])) {
            $data['blog_featured_post'] = Request::p()->post['blog_featured_post'];
        } else {
            $data['blog_featured_post'] = Config::get('blog_featured_post');
        }
        
        Theme::model('content/post');
        
        if (isset(Request::p()->post['blog_featured_post'])) {
            $posts = explode(',', Request::p()->post['blog_featured_post']);
        } else {
            $posts = explode(',', Config::get('blog_featured_post'));
        }
        
        $data['posts'] = array();
        
        foreach ($posts as $post_id) {
            $post_info = ContentPost::getPost($post_id);
            
            if ($post_info) {
                $data['posts'][] = array('post_id' => $post_info['post_id'], 'name' => $post_info['name']);
            }
        }
        
        $data['widgets'] = array();
        
        if (isset(Request::p()->post['blog_featured_widget'])) {
            $data['widgets'] = Request::p()->post['blog_featured_widget'];
        } elseif (Config::get('blog_featured_widget')) {
            $data['widgets'] = Config::get('blog_featured_widget');
        }
        
        Theme::model('design/layout');
        
        $data['layouts'] = DesignLayout::getLayouts();
        
        Theme::loadjs('javascript/widget/blog_featured', $data);
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::renderControllers($data);
        
        Response::setOutput(View::make('widget/blog_featured', $data));
    }
    
    private function validate() {
        if (!User::hasPermission('modify', 'widget/blog_featured')) {
            $this->error['warning'] = Lang::get('lang_error_permission');
        }
        
        if (isset(Request::p()->post['blog_featured_widget'])) {
            foreach (Request::p()->post['blog_featured_widget'] as $key => $value) {
                if (!$value['image_width'] || !$value['image_height']) {
                    $this->error['image'][$key] = Lang::get('lang_error_image');
                }
            }
        }
        
        Theme::listen(__CLASS__, __FUNCTION__);
        
        return !$this->error;
    }
}
