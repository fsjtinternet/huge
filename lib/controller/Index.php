<?php
namespace controller;

use huge\facade\View;

class Index{
    public function index(){
        $assign = ['title' => 'HugePHP - A Powerful PHP Framework'];
        return View::fetch('index', $assign);
    }
}
?>