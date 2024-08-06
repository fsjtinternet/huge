<?php
namespace huge;

use Frame;

class View{
    public function __call($name, $args){
        $v = new \think\Template(Frame::config("template"));
        if (method_exists($v, $name)){
            return call_user_func_array([$v, $name], $args);
        }
        return false;
    }
}
?>