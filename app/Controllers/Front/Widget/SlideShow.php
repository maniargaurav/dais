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

namespace App\Controllers\Front\Widget;
use App\Controllers\Controller;

class SlideShow extends Controller {
    public function index($setting) {
        static $widget = 0;
        
        Theme::model('design/banner');
        Theme::model('tool/image');
        
        $data['width'] = $setting['width'];
        $data['height'] = $setting['height'];
        
        $data['banners'] = array();
        
        if (isset($setting['banner_id'])) {
            $results = $this->model_design_banner->getBanner($setting['banner_id']);
            
            foreach ($results as $result) {
                if (file_exists(Config::get('path.image') . $result['image'])) {
                    $result['link'] = (Config::get('config_ucfirst')) ? $this->url->cap_slug($result['link']) : $result['link'];
                    
                    $data['banners'][] = array('title' => $result['title'], 'link' => $result['link'], 'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']));
                }
            }
        }
        
        $data['widget'] = $widget++;
        
        $data = Theme::listen(__CLASS__, __FUNCTION__, $data);
        
        return Theme::view('widget/slide_show', $data);
    }
}
