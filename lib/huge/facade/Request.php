<?php
namespace huge\facade;

use huge\Facade;

class Request extends Facade{
    public static function getFacadeName(){
        return \huge\Request::class;
    }
}
?>