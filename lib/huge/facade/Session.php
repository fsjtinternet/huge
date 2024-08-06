<?php
namespace huge\facade;

use huge\Facade;

class Session extends Facade{
    public static function getFacadeName(){
        return \huge\Session::class;
    }
}
?>