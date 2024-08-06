<?php
class Frame{
    /**
     * 获取配置信息
     * @access public
     * @param string $configFileName 配置文件名称
     * @return array|bool 有配置文件且符合规范则返回array
     */
    public static function config(string $configFileName){
        if (file_exists(ROOT_PATH."config/".$configFileName.".php")){
            include ROOT_PATH."config/".$configFileName.".php";
            return $config;
        }
        else {
            return false;
        }
    }
    /**
     * 写入日志
     * @access public
     * @param string $content
     * @return void
     */
    public static function writelog(string $content) : void {
        $filePath = ROOT_PATH."logs/".date("Y")."/".date("m")."/".date("d").".log";
        $time = date("c");
        if (!file_exists($filePath)){
            $dirPath = dirname($filePath);
            if (!is_dir($dirPath)){
                mkdir($dirPath, 0755, true);
            }
            file_put_contents($filePath, "[{$time}] {$content}
");
        }
        else file_put_contents($filePath, "[{$time}] {$content}
", FILE_APPEND);
    }
    /**
     * 向客户端抛出错误
     * @access public
     * @param string $type 错误类型
     * @param string $message 错误信息
     * @param mixed $extraMsg 额外错误信息
     * @param bool $option 是否为自定义错误
     * @return string 渲染后的页面HTML源码
     */
    public static function throwError(string $type, string $message, mixed $extraMsg = null, bool $option = false){
        $backtrace = debug_backtrace()[0];
        $errorMsgList = [
            "controller" => [
                "404" => "Controller doesn't exist",
            ],
            "action" => [
                "404" => "Action doesn't exist",
            ],
        ];
        $errorMsg = $message;
        if (!$option) $errorMsg = $errorMsgList[$type][$message];
        $dataList = [
            "errorMsg" => $errorMsg.(is_null($extraMsg) ? null : " - ".$extraMsg),
            "from" => "File : ".$backtrace["file"]." ; Line : ".$backtrace["line"],
            "getData" => $_GET,
            "postData" => $_POST,
            "cookie" => huge\facade\Cookie::all(),
            "session" => huge\facade\Session::all(),
            "serverData" => $_SERVER
        ];
        $isDebug = env("IS_DEBUG");
        if ($isDebug) return huge\facade\View::fetch("error_debug", $dataList);
        else return huge\facade\View::fetch("error");
    }
    /**
     * @param int $length 长度
     * @param int $type 类型
     * @return string
     */
    public static function randString(int $length = 32, int $type = 4): string {
        $rand = '';
        switch ($type) {
            case 1:
                $randstr = '0123456789';
                break;
            case 2:
                $randstr = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 3:
                $randstr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            default:
                $randstr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }
        $max = strlen($randstr) - 1;
        mt_srand((double)microtime() * 1000000);
        for ($i = 0; $i < $length; $i++) {
            $rand .= $randstr[mt_rand(0, $max)];
        }
        return $rand;
    }
}
?>