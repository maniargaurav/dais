<?php

/*
|--------------------------------------------------------------------------
|   Dais
|--------------------------------------------------------------------------
|
|   This file is part of the Dais Framework package.
|   
|   (c) Vince Kronlein <vince@dais.io>
|   
|   For the full copyright and license information, please view the LICENSE
|   file that was distributed with this source code.
|   
*/

namespace App\Plugin\Example;

use Dais\Contracts\PluginRegistryContract;

class Register extends Plugin implements PluginRegistryContract {
    
    public function __construct() {
        Plugin::setPlugin('example');
    }
    
    public function add() {
        // set all event handlers
        PluginModel::setEventHandler('admin_edit_product', array(
            'plugin' => 'example',
            'file'   => 'admin/events/admin_event',
            'method' => 'editProduct'
        ));
        
        // set all hooks handlers
        PluginModel::setHookHandler('admin_controllers', array(
            'class'    => 'tool/test',
            'method'   => 'index',
            'type'     => 'post',
            'plugin'   => 'example',
            'file'     => 'admin/hooks/controller_hooks',
            'callback' => 'exampleHook',
            'args'     => array(
                'heading_title' => 'Example Test Page',
                'item_title'    => 'Item title'
            )
        ));
        
        PluginModel::setHookHandler('admin_controllers', array(
            'class'    => 'tool/test',
            'method'   => 'index',
            'type'     => 'pre',
            'plugin'   => 'example',
            'file'     => 'admin/hooks/controller_hooks',
            'callback' => 'preHook'
        ));
    }
    
    public function remove() {
        // remove all event handlers
        PluginModel::removeEventHandler('admin_edit_product', array(
            'plugin' => 'example',
            'file'   => 'admin/events/admin_event',
            'method' => 'editProduct'
        ));
        
        // remove all hook handlers
        PluginModel::removeHookHandler('admin_controllers', array(
            'class'    => 'tool/test',
            'method'   => 'index',
            'type'     => 'post',
            'plugin'   => 'example',
            'file'     => 'admin/hooks/controller_hooks',
            'callback' => 'exampleHook',
            'args'     => array(
                'heading_title' => 'Example Test Page',
                'item_title'    => 'Item title'
            )
        ));
        
        PluginModel::removeHookHandler('admin_controllers', array(
            'class'    => 'tool/test',
            'method'   => 'index',
            'type'     => 'pre',
            'plugin'   => 'example',
            'file'     => 'admin/hooks/controller_hooks',
            'callback' => 'preHook'
        ));
    }
}
