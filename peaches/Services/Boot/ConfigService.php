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

namespace Dais\Services\Boot;

use Dais\Services\Providers\Boot\Config;
use Dais\Engine\Container;
use Dais\Contracts\ServiceContract;


class ConfigService implements ServiceContract {

	private $prefix;

	public function register(Container $app) {
		$this->prefix = env('DB_PREFIX', '');
		
		$config = new Config;

        $configs = $this->build($app['setting.config']);

        foreach ($configs as $key => $value):
            $config->set($key, $value);
        endforeach;

        App::removeSettingConfig();
        
		$app['config'] = function ($app) use($config) {
            return $config;
        };
	}

	private function build($config) {
		if ($config['active.facade'] === FRONT_FACADE):
            if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))):
                $store_query = DB::query("
                    SELECT * 
                    FROM {$this->prefix}store 
                    WHERE 
                        REPLACE(`ssl`, 'www.', '') = '" . DB::escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']) , '/.\\') . '/') . "'
                ");
                
                if ($store_query->num_rows):
                    $config['config_store_id'] = $store_query->row['store_id'];
                    $config['config_url']      = $store_query->row['url'];
                    $config['config_ssl']      = $store_query->row['ssl'];
                else:
                    $config['config_store_id'] = 0;
                    $config['config_url']      = $config['http.server'];
                    $config['config_ssl']      = $config['https.server'];
                endif;
                
                $image_url = $config['https.server'] . 'image/';
            else:
                $store_query = DB::query("
                    SELECT * 
                    FROM {$this->prefix}store 
                    WHERE 
                        REPLACE(`url`, 'www.', '') = '" . DB::escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']) , '/.\\') . '/') . "'
                ");
                
                if ($store_query->num_rows):
                    $config['config_store_id'] = $store_query->row['store_id'];
                    $config['config_url']      = $store_query->row['url'];
                    $config['config_ssl']      = $store_query->row['ssl'];
                else:
                    $config['config_store_id'] = 0;
                    $config['config_url']      = $config['http.server'];
                    $config['config_ssl']      = $config['https.server'];
                endif;
                
                $image_url = $config['http.server'] . 'image/';
            endif;
            
            define('IMAGE_URL', $image_url);
        else:
            $config['config_store_id'] = 0;
            $config['config_url']      = $config['http.server'];
            $config['config_ssl']      = $config['https.server'];
            
            define('IMAGE_URL', $config['config_url'] . 'image/');
        endif;
        
        $query = DB::query("
            SELECT * 
            FROM {$this->prefix}setting 
            WHERE store_id = '0' 
            OR store_id = '" . (int)$config['config_store_id'] . "' 
            ORDER BY store_id ASC
        ");
        
        $settings = $query->rows;
        
        foreach ($settings as $setting):
            if (!$setting['serialized']):
                $config[$setting['item']] = $setting['data'];
            else:
                $config[$setting['item']] = unserialize($setting['data']);
            endif;
        endforeach;

        // theme name via facade
        switch($config['active.facade']):
            case ADMIN_FACADE:
                $theme_name = $config['config_admin_theme'];
                break;
            case FRONT_FACADE:
                $theme_name = $config['config_theme'];
                break;
        endswitch;

        $config['theme.name'] = $theme_name;

        $config['path.filecache'] = $config['path.asset'] . $theme_name . SEP . 'compiled' . SEP;

        // Image Upload Url for Summernote Editor
        if ($config['config_secure']):
            $img_url = $config['https.server'] . 'image/';
        else:
            $img_url = $config['http.server'] . 'image/';
        endif;
        
        define('PUBLIC_IMAGE', $img_url);

        return $config;
	}
}