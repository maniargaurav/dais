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

namespace Dais\Library;
use Dais\Engine\Container;
use Dais\Service\LibraryService;
use Dais\Service\PluginServiceModel;

class Event extends LibraryService {
    
    private $events = array();
    
    public function __construct(Container $app, PluginServiceModel $model) {
        parent::__construct($app);
        
        $this->model = $model;
        $this->registerEvents();
    }
    
    public function registerEvents() {
        $events = $this->model->getEventHandlers();
        
        foreach ($events as $event):
            $handlers = unserialize($event['handlers']);
            
            foreach ($handlers as $handler):
                if (!array_key_exists($event['event'], $this->events)):
                    $this->events[$event['event']] = array();
                endif;
                
                if (is_string($handler)):
                    $this->events[$event['event']][] = $handler;
                endif;
            endforeach;
        endforeach;
        
        parent::$app['plugin_events'] = $this->events;
    }
    
    public function unregisterEvents() {
    }
    
    public function trigger($event, $data = array()) {
        if (!array_key_exists($event, $this->events)):
            return true;
        endif;
        
        foreach ($this->events[$event] as $handler):
            $segments = explode(SEP, $handler);
            $method   = array_pop($segments);
            $class    = Naming::class_from_filename(parent::$app['prefix.plugin'] . SEP . implode(SEP, $segments));
            
            $arguments = !empty($data) ? $data : null;

            $class = new $class(parent::$app);
            
            if (is_callable(array($class, $method))):
                return call_user_func_array(array($class, $method), array($arguments));
            endif;
        endforeach;
        
        return true;
    }
}
