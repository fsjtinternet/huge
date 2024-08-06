<?php
namespace huge\session;

interface sessionInterface{
    public function isDefined(string $sessionId) : bool ;
    public function sessionInit(string $sessionId) : void ;
    public function getSessionValue(string $sessionId) : array ;
    public function writeSessionValue(string $sessionId, array $data) : bool ;
    public function destorySession(string $sessionId) : bool ;
}
?>