<?php
namespace huge;

class Route{  
    public $routes = [];
    public function rule(string $rule, string $route, string $methods = "*") : void {
        $list = [
            "*" => "any",
            "GET" => "get",
            "POST" => "post",
            "DELETE" => "del",
            "PUT" => "put",
            "PATCH" => "patch",
        ];
        if (strpos($methods, "|") !== false){
            $methodsList = implode("|", $methods);
            foreach ($methodsList as $method){
                $methodName = $list[$method];
                $this->{$methodName}();
            }
        }
        else {
            $methodName = $list[$method];
            $this->{$methodName}();
        }
    }
    public function has(string $rule, string $method) : bool {
        $routes = $this->routes;
        if (array_key_exists($rule, $routes)){
            foreach ($routes[$rule] as $route){
                if ($route["method"] === $method || $route["method"] === "*") return true;
            }
            return false;
        }
        else{
            return false;
        }
    }
    public function any(string $rule, string $route) : void {
        if ($this->has($rule, "*") === false){
            $this->routes[$rule][] = ["route" => $route, "method" => "*"];
        }
    }
    public function get(string $rule, string $route) : void {
        if ($this->has($rule, "GET") === false){
            $this->routes[$rule][] = ["route" => $route, "method" => "GET"];
        }
    }
    public function post(string $rule, string $route) : void {
        if ($this->has($rule, "POST") === false){
            $this->routes[$rule][] = ["route" => $route, "method" => "POST"];
        }
    }
    public function del(string $rule, string $route) : void {
        if ($this->has($rule, "DELETE") === false){
            $this->routes[$rule][] = ["route" => $route, "method" => "DELETE"];
        }
    }
    public function put(string $rule, string $route) : void {
        if ($this->has($rule, "PUT") === false){
            $this->routes[$rule][] = ["route" => $route, "method" => "PUT"];
        }
    }
    public function patch(string $rule, string $route) : void {
        if ($this->has($rule, "PATCH") === false){
            $this->routes[$rule][] = ["route" => $route, "method" => "PATCH"];
        }
    }
    public function prase(string $rule, string $method) : string {
        if ($this->has($rule, $method)){
            foreach ($this->routes[$rule] as $route){
                if ($route["method"] === $method || $route["method"] === "*") return $route["route"];
            }
        }
    }
    public function out(){
        return $this->routes;
    }
}
?>