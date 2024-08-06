<?php
namespace huge;

class Cookie{
    /**
     * @access public
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return void
     */
    public function set(string $name, string $value, int $expire = 3600, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false) : void {
        setcookie($name, $value, time() + $expire, $path, $domain, $secure, $httponly);
    }
    /**
     * @access public
     * @param string $name
     * @param string $default
     * @return string
     */
    public function get(string $name, string $default = "") : string {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }
    /**
     * @access public
     * @param string $name
     * @return bool
     */
    public function has(string $name) : bool {
        return isset($_COOKIE[$name]);
    }
    /**
     * @access public
     * @return array
     */
    public function all() : array {
        return $_COOKIE;
    }
    /**
     * @access public
     * @param string $name
     * @return void
     */
    public function del(string $name) : void {
        setcookie($name, "", time() - 3600, "/");
    }
}
?>