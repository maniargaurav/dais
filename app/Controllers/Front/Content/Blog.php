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

namespace App\Controllers\Front\Content;
use App\Controllers\Controller;

class Blog extends Controller {

    public function index() {
        $data = Theme::language('content/home');
        
        Theme::setTitle(Config::get('config_name'));
        Theme::setDescription(Config::get('config_meta_description'));
        
        Theme::setOgType('article');
        Theme::setOgDescription(html_entity_decode(Config::get('config_meta_description'), ENT_QUOTES, 'UTF-8'));
        
        Breadcrumb::add(sprintf(Lang::get('lang_heading_title'), Config::get('config_name')), 'content/home');
        
        Theme::model('content/post');
        Theme::model('content/category');
        
        if (isset($this->request->get['sort'])):
            $sort = $this->request->get['sort'];
        else:
            $sort = 'p.date_added';
        endif;
        
        if (isset($this->request->get['order'])):
            $order = $this->request->get['order'];
        else:
            $order = 'DESC';
        endif;
        
        if (isset($this->request->get['page'])):
            $page = $this->request->get['page'];
        else:
            $page = 1;
        endif;
        
        if (isset($this->request->get['limit'])):
            $limit = $this->request->get['limit'];
        else:
            $limit = Config::get('config_catalog_limit');
        endif;
        
        $post_total = $this->model_content_post->getTotalPosts();
        $posts = $this->model_content_post->getPosts();
        
        $data['posts'] = array();
        
        foreach ($posts as $post):
            if ($post['image']):
                $image = IMAGE_URL . $post['image'];
                // remove resizing and allow img-responsive to handle sizing
            else:
                $image = '';
            endif;
                
            if (Config::get('blog_comment_status')):
                $rating = (int)$post['rating'];
            else:
                $rating = false;
            endif;
                
            $categories = $this->model_content_category->getCategoriesByPostId($post['post_id']);
            
            $posted_in = array();
            $posted_in_categories = '';
            
            if ($categories):
                foreach ($categories as $category):
                    $posted_in[] = sprintf(Lang::get('lang_text_posted_categories'), $category['href'], $category['name']);
                endforeach;
            endif;
            
            if (!empty($posted_in)):
                $posted_in_categories = implode(", ", $posted_in);
            endif;
            
            $comment_text = ($post['comments'] == 1) ? rtrim(Lang::get('lang_text_comments'), 's') : Lang::get('lang_text_comments');
            
            $data['posts'][] = array('post_id' => $post['post_id'], 'author_name' => $post['author_name'], 'thumb' => $image, 'name' => $post['name'], 'short' => $this->encode->substr(strip_tags(html_entity_decode($post['description'], ENT_QUOTES, 'UTF-8')), 0, 450) . '..', 'blurb' => $this->encode->substr(strip_tags(html_entity_decode($post['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..', 'rating' => $rating, 'views' => sprintf(Lang::get('lang_text_views'), (int)$post['viewed']), 'comments' => sprintf($comment_text, (int)$post['comments']), 'href' => Url::link('content/post', 'post_id=' . $post['post_id']), 'comments_href' => Url::link('content/post', 'post_id=' . $post['post_id'] . '&to_comments=1'), 'author_href' => Url::link('content/search', '&filter_author_id=' . $post['author_id']), 'date_added' => date(Lang::get('lang_post_date'), strtotime($post['date_added'])), 'categories' => $posted_in_categories);
        endforeach;
            
        $url = '';
        
        if (isset($this->request->get['sort'])):
            $url.= '&sort=' . $this->request->get['sort'];
        endif;
        
        if (isset($this->request->get['order'])):
            $url.= '&order=' . $this->request->get['order'];
        endif;
        
        if (isset($this->request->get['limit'])):
            $url.= '&limit=' . $this->request->get['limit'];
        endif;
        
        $data['pagination'] = Theme::paginate($post_total, $page, $limit, Lang::get('lang_text_pagination'), Url::link('content/home', $url . '&page={page}'));
        
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;
        
        // Search
        if (isset($this->request->get['filter_name'])):
            $data['filter_name'] = $this->request->get['filter_name'];
        else:
            $data['filter_name'] = '';
        endif;
            
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        $data = Theme::render_controllers($data);
        
        $this->response->setOutput(Theme::view('content/home', $data));
    }
}
