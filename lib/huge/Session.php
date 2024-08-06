<?php
namespace huge;

use huge\facade\Cookie;

class Session{
    private $config, $storeExec;
    /**
     * @param array $config
     * @return void
     */
    public function setConfig(array $config) : void {
        $this->config = $config;
        $storeName = "huge\\session\\store\\".$config["storeType"];
        $this->storeExec = new $storeName($config);
        if (isset($config["sessionId"])){
            if ($this->storeExec->isDefined($config["sessionId"])){
                $this->sessionId = $config["sessionId"];
            }
            else{
                $this->init();
            }
        }
        else{
            $this->init();
        }
    }
    /**
     * @access private
     * @return void
     */
    private function init(){
        $sessionId = md5(time().rand(1,99999));
        $this->sessionId = $sessionId;
        Cookie::set($this->config["sessionName"], $sessionId, $this->config["expireTime"]);
        $this->storeExec->sessionInit($sessionId);
    }
    /**
     * @access public
     * @param string $name
     * @param string $value
     * @return void
     */
    public function set(string $name, string $value) : void {
        $sessionValueArr = $this->storeExec->getSessionValue($this->sessionId);
        $sessionValueArr[$name] = $value;
        $this->storeExec->writeSessionValue($this->sessionId, $sessionValueArr);
    }
    /**
     * @access public
     * @param string $name
     * @param string $default
     * @return string
     */
    public function get(string $name, string $default = "") : string {
        $sessionValueArr = $this->storeExec->getSessionValue($this->sessionId);
        return isset($sessionValueArr[$name]) ? $sessionValueArr[$name] : $default;
    }
    /**
     * @access public
     * @param string $name
     * @return bool
     */
    public function has(string $name) : bool {
        $sessionValueArr = $this->storeExec->getSessionValue($this->sessionId);
        return isset($sessionValueArr[$name]);
    }
    /**
     * @access public
     * @return array
     */
    public function all() : array {
        $sessionValueArr = $this->storeExec->getSessionValue($this->sessionId);
        return $sessionValueArr;
    }
    /**
     * @access public
     * @param string $name
     * @return void
     */
    public function del(string $name) : void {
        $sessionValueArr = $this->storeExec->getSessionValue($this->sessionId);
        unset($sessionValueArr[$name]);
    }
    /**
     * @access public
     * @return bool
     */
    public function clear() : bool {
        Cookie::del($this->config["sessionName"]);
        return $this->storeExec->destorySession($this->sessionId);
    }
}
?>