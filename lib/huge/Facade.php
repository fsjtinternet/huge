<?php
namespace huge;

class Facade{
    protected static $instance;
    private static function getFacadeRoot(){
        $instanceName = static::getFacadeName();
        self::$instance[$instanceName] = new $instanceName;
        return self::$instance[$instanceName];
    }
    public static function __callStatic($method, $args){
        $instance = self::getFacadeRoot();
        return $instance->$method(...$args);
    }
}
?>