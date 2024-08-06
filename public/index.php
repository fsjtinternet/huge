<?php
//+===================================================+
//|         Author          :           FSJT          |
//|===================================================|
//|          Email          :    fsjtnet@vip.qq.com   |
//|===================================================|
//|      Copyright 2024 FSJT.All Rights Reserved      |
//|===================================================|
//|            ANYONE CAN CHANGE THE WORLD            |
//+===================================================+
define('ROOT_PATH', __DIR__.'/../');
//加载必要文件并初始化

require_once ROOT_PATH.'vendor/autoload.php';//Composer
require_once ROOT_PATH.'lib/load.php';//框架自动加载函数
require_once ROOT_PATH.'lib/functions.php';//框架必要函数
require_once ROOT_PATH.'lib/helper.php';//助手函数文件
require_once ROOT_PATH.'lib/common.php';//应用公共文件

Autoload::register();//自动加载函数注册
require_once ROOT_PATH."lib/route.php";
huge\facade\Db::setConfig(config("database"));//数据库管理器注册
huge\facade\Request::setConfig($_REQUEST, $_GET, $_POST);
if (config("session")["isEnabled"]){
    $sessionConfig = config("session");
    if (session_name() !== $sessionConfig["sessionName"]) huge\facade\Cookie::del(session_name());
    if (huge\facade\Cookie::has($sessionConfig["sessionName"])) $sessionConfig["sessionId"] = huge\facade\Cookie::get($sessionConfig["sessionName"]);
    huge\facade\Session::setConfig($sessionConfig);//Session管理器注册
}


$s = isset($_GET['s']) ? trim($_GET['s'], "/") : "Index/index" ;
if (huge\facade\Route::has($s, $_SERVER["REQUEST_METHOD"])){
    $s = huge\facade\Route::prase($s, $_SERVER["REQUEST_METHOD"]);
}
$urlPathInfo = explode("/", $s);
$controllerName = ucfirst($urlPathInfo[0]);
if (!empty($urlPathInfo)){
    @$actionName = $urlPathInfo[1];
    if ($actionName === null || !isset($actionName)) $actionName = "index";
}


if (count($urlPathInfo) > 2){
    $pathInfoArgs = [];
    for ($i = 2; $i < count($urlPathInfo) - 1; $i += 2){
        if (isset($urlPathInfo[$i]) && isset($urlPathInfo[$i + 1])){
            $pathInfoArgs[$urlPathInfo[$i]] = $urlPathInfo[$i + 1];
        }
    }
}

if (file_exists(ROOT_PATH."lib/controller/".$controllerName.".php")){
    $controllerFullName = "controller\\".$controllerName;
    $controllerExec = new $controllerFullName();
    if (method_exists($controllerExec, $actionName)){
        huge\facade\Request::setName($controllerName, $actionName);
        $reflectionClass = new ReflectionClass($controllerFullName);
        $reflectionMethods = $reflectionClass->getMethods();
        foreach ($reflectionMethods as $reflectionMethod){
            if ($reflectionMethod->getName() === $actionName){
                $reflectionParameters = $reflectionMethod->getParameters();
                $args = [];
                $wrongArgs = 0;
                $wrongArgsList = [];
                foreach ($reflectionParameters as $param){
                    if (!is_null($param->getType())){
                        if (class_exists($param->getType()->getName(), true)){
                            $className = $param->getType()->getName();
                            $args[$param->getName()] = new $className();
                        }
                    }
                    else {
                        if (huge\facade\Request::isSet("param", $param->getName()) && isset($pathInfoArgs[$param->getName()])){
                            $args[$param->getName()] = $pathInfoArgs[$param->getName()];
                        }
                        elseif (huge\facade\Request::isSet("param", $param->getName())){
                            $args[$param->getName()] = huge\facade\Request::param($param->getName());
                        }
                        elseif (isset($pathInfoArgs[$param->getName()])){
                            $args[$param->getName()] = $pathInfoArgs[$param->getName()];
                        }
                        else {
                            $wrongArgs += 1;
                            $wrongArgsList[] = $param->getName();
                        }
                    }
                }
                if ($wrongArgs !== 0){
                    Frame::writelog("Args are wrong in ".$controllerName."->".$actionName.".Args : ".json_encode($_REQUEST));
                    echo Frame::throwError("action", "The required parameters for the function haven't been submitted", "Missing Parameters : ".implode(", ", $wrongArgsList), true);
                }
                else {
                    Frame::writelog($controllerName."->".$actionName.".Args : ".json_encode(isset($pathInfoArgs) ? array_merge($_REQUEST, $pathInfoArgs) : $_REQUEST));
                    echo call_user_func_array([$controllerExec, $actionName], $args);
                }
            }
        }
        
    }
    else {
        Frame::writelog("Action ".$actionName." doesn't exist in ".$controllerName);
        echo Frame::throwError("action", "404", "Controller : ".$controllerName);
    }
}
else {
    $emptyControllerName = "controller\\".config("app")["emptyController"];
    if (class_exists($emptyControllerName, true)){
        $emptyControllerExec = new $emptyControllerName;
        echo $emptyControllerExec->error();
    }
    else {
        Frame::writelog("Controller ".$controllerName." doesn't exist");
        echo Frame::throwError("controller", "404", "Name : ".$controllerName);
    }
}