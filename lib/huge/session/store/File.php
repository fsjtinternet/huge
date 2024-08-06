<?php
namespace huge\session\store;

use huge\session\sessionInterface;

class File implements sessionInterface{
    public function __construct(array $config){
        $this->path = $config['sessionPath'];
        $this->prefix = $config['sessionPrefix'];
    }
    /**
     * @access private
     * @param string $sessionId
     * @return string
     */
    private function getSessionFileName(string $sessionId) : string {
        return $this->path.$this->prefix.$sessionId.".txt";
    }
     /**
      * @access private
      * @param string $fileName
      * @return array
      */
    private function readFile(string $fileName) : array {
        $fileContent = file_get_contents($fileName);
        $fileContent = json_decode($fileContent, true);
        if (is_null($fileContent)) $fileContent = [];
        return $fileContent;
    }
    /**
     * @access public
     * @param string $sessionId
     * @return bool
     */
    public function isDefined(string $sessionId) : bool {
        $fileName = $this->getSessionFileName($sessionId);
        return file_exists($fileName);
    }
    /**
     * @access public
     * @param string $sessionId
     * @return void
     */
    public function sessionInit(string $sessionId) : void {
        $fileName = $this->getSessionFileName($sessionId);
        $handle = fopen($fileName, "w");
    }
    /**
     * @access public
     * @param string $sessionId
     * @return array
     */
    public function getSessionValue(string $sessionId) : array {
        $sessionFileName = $this->getSessionFileName($sessionId);
        $fileContent = $this->readFile($sessionFileName);
        return $fileContent;
    }
    /**
     * @access public
     * @param string $sessionId
     * @param array $data
     * @return bool
     */
    public function writeSessionValue(string $sessionId, array $data) : bool {
        return file_put_contents($this->getSessionFileName($sessionId), json_encode($data));
    }
    /**
     * @access public
     * @param string $sessionId
     * @return bool
     */
    public function destorySession(string $sessionId) : bool {
        return unlink($this->getSessionFileName($sessionId));
    }
}
?>
