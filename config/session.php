<?php
$config = [
    'isEnabled'       => true,//是否启用Session
    'storeType'       => 'File',//存储方法
    'expireTime'      => 3600,//Session过期时间
    'sessionName'     => 'HUGESESSID',//Session Name
    
    'sessionPrefix'   => 'session_',//Session文件存储名称
    'sessionPath'     => ROOT_PATH.'cache/session/',//Session存储路径
    
    'tableName'       => 'cache_session',//数据库表名
    'keyName'         => 'sessionId',//数据库键名
    'valueName'       => 'sessionValue',//数据库值名
];
?>