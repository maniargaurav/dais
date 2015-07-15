<?php

/*
|--------------------------------------------------------------------------
|   Dais
|--------------------------------------------------------------------------
|
|   This file is part of the Dais Framework package.
|    
|    (c) Vince Kronlein <vince@dais.io>
|    
|    For the full copyright and license information, please view the LICENSE
|    file that was distributed with this source code.
|    
*/

/*
|--------------------------------------------------------------------------
|   Stolen from Illuminate\Support\Facades\Facade from Laravel 5
|--------------------------------------------------------------------------  
*/

namespace Dais\Support;

use Mockery;
use RuntimeException;
use Mockery\MockInterface;

abstract class Facade {
    
    protected static $app;

    protected static $resolvedInstance;

    public static function swap($instance) {
        static::$resolvedInstance[static::getFacadeAccessor()] = $instance;

        static::$app->instance(static::getFacadeAccessor(), $instance);
    }

    public static function shouldReceive() {
        $name = static::getFacadeAccessor();

        if (static::isMock()):
            $mock = static::$resolvedInstance[$name];
        else:
            $mock = static::createFreshMockInstance($name);
        endif;

        return call_user_func_array([$mock, 'shouldReceive'], func_get_args());
    }

    protected static function createFreshMockInstance($name) {
        static::$resolvedInstance[$name] = $mock = static::createMockByName($name);

        if (isset(static::$app)):
            static::$app->instance($name, $mock);
        endif;

        return $mock;
    }

    protected static function createMockByName($name) {
        $class = static::getMockableClass($name);

        return $class ? Mockery::mock($class) : Mockery::mock();
    }

    protected static function isMock() {
        $name = static::getFacadeAccessor();

        return isset(static::$resolvedInstance[$name]) && static::$resolvedInstance[$name] instanceof MockInterface;
    }

    protected static function getMockableClass() {
        if ($root = static::getFacadeRoot()):
            return get_class($root);
        endif;
    }

    public static function getFacadeRoot() {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    protected static function getFacadeAccessor() {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    protected static function resolveFacadeInstance($name) {
        if (is_object($name)):
            return $name;
        endif;

        if (isset(static::$resolvedInstance[$name])):
            return static::$resolvedInstance[$name];
        endif;

        return static::$resolvedInstance[$name] = static::$app[$name];
    }

    public static function clearResolvedInstance($name) {
        unset(static::$resolvedInstance[$name]);
    }

    public static function clearResolvedInstances() {
        static::$resolvedInstance = [];
    }

    public static function getFacadeApplication() {
        return static::$app;
    }

    public static function setFacadeApplication($app) {
        static::$app = $app;
    }

    public static function __callStatic($method, $args) {
        $instance = static::getFacadeRoot();

        switch (count($args)):
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array([$instance, $method], $args);
        endswitch;
    }
}
