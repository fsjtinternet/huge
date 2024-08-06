<?php
namespace controller;

use huge\facade\View;

class Error{
    public function __call($method, $args){
        //header('HTTP/1.1 404 Not Found');
        return View::fetch("404");
    }
}
?>