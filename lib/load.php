<?php
class Autoload{
    public static function register(){
        spl_autoload_register(array(new self, 'autoload'));
    }
    public static function autoload($className){
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . $className;
        $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $filePath) . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
            return true;
        }
        else {
            return false;
        }
    }
}
?>