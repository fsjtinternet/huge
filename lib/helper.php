<?php
//助手函数定义
if (!function_exists("config")){
    function config($configFileName){
        if (file_exists(ROOT_PATH."config/".$configFileName.".php")){
            include ROOT_PATH."config/".$configFileName.".php";
            return $config;
        }
        else {
            return false;
        }
    }
}
if (!function_exists("view")){
    function view($templateName, array $argument = []){
        if ($argument === []) return huge\facade\View::fetch($templateName);
        else return huge\facade\View::fetch($templateName, $argument);
    }
}
if (!function_exists("display")){
    function display($templateValue, array $argument = []){
        if ($argument === []) return huge\facade\View::display($templateValue);
        else return huge\facade\View::display($templateValue, $argument);
    }
}
if (!function_exists("cookie")){
    function cookie($name, $value = "", $expire = ""){
        if ($value === "" && $expire === ""){
            return huge\facade\Cookie::get($name);
        }
        if ($value === null && $expire === ""){
            return huge\facade\Cookie::del($name);
        }
        if ($value !== "" && $expire !== ""){
            return huge\facade\Cookie::set($name, $value, $expire);
        }
    }
}
if (!function_exists("env")){
    function env($name){
        return parse_ini_file(ROOT_PATH.".env")[$name];
    }
}
?>