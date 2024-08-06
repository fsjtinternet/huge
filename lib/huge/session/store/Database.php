<?php
namespace huge\session\store;

use huge\facade\Db;
use huge\session\sessionInterface;

class Database implements sessionInterface{
    public function __construct(array $config){
        $this->table = $config["tableName"];
        $this->keyName = $config["keyName"];
        $this->valueName = $config["valueName"];
    }
    /**
     * @access public
     * @param string $sessionId
     * @return bool
     */
    public function isDefined(string $sessionId) : bool {
        return Db::table($this->table)->where($this->keyName, $sessionId)->find() !== null;
    }
    /**
     * @access public
     * @param string $sessionId
     * @return void
     */
    public function sessionInit(string $sessionId) : void {
        Db::table($this->table)->save([$this->keyName => $sessionId, $this->valueName => "[]"]);
    }
    /**
     * @access public
     * @param string $sessionId
     * @return array
     */
    public function getSessionValue(string $sessionId) : array {
        return json_decode(Db::table($this->table)->where($this->keyName, $sessionId)->find()[$this->valueName], true);
    }
    /**
     * @access public
     * @param string $sessionId
     * @param array $data
     * @return bool
     */
    public function writeSessionValue(string $sessionId, array $data) : bool {
        return Db::table($this->table)->where($this->keyName, $sessionId)->update([$this->valueName => json_encode($data)]) !== 0;
    }
    /**
     * @access public
     * @param string $sessionId
     * @return bool
     */
    public function destorySession(string $sessionId) : bool {
        Db::table($this->table)->where($this->keyName, $sessionId)->delete();
        return true;
    }
}
?>