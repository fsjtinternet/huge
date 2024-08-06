<?php
namespace huge;

class Request{
    private $controllerName, $actionName;
    /**
     * @access public
     * @param string $controllerName
     * @param string $actionName
     * @return void
     */
    public function setName(string $controllerName, string $actionName) : void {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
    }
    /**
     * @access public
     * @param string $type
     * @param string $name
     * @return bool
     */
    public function isSet(string $type, string $name) : bool {
        $typeList = [
            'param' => $_REQUEST,
            'get' => $_GET,
            'post' => $_POST,
        ];
        return isset($typeList[$type][$name]);
    }
    /**
     * @access public
     * @param string $name
     * @return mixed
     */
    public function param(string $name) : mixed {
        return $_REQUEST[$name];
    }
    /**
     * @access public
     * @param string $name
     * @return mixed
     */
     public function get(string $name) : mixed {
         return $_GET[$name];
     }
     /**
     * @access public
     * @param string $name
     * @return mixed
     */
     public function post(string $name) : mixed {
         return $_POST[$name];
     }
}
?>