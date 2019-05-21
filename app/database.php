<?php
//error_reporting(0);
//defined('IN_IA') or define('IN_IA', true);

$database=[
    // 数据库类型
    'type'            => 'mysql',

    'dsn'             => '',
    // 数据库连接参数
    'params'          => [],
    // 数据库编码默认采用utf8
    'charset'         => 'utf8',
    // 数据库表前缀
    'prefix'          => 'dayi_',
    // 数据库调试模式
    'debug'           => true,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy'          => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate'     => false,
    // 读写分离后 主服务器数量
    'master_num'      => 1,
    // 指定从服务器序号
    'slave_no'        => '',
    // 是否严格检查字段是否存在
    'fields_strict'   => true,
    // 数据集返回类型
    'resultset_type'  => 'array',
    // 自动写入时间戳字段
    'auto_timestamp'  => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => false,
];
if(file_exists(__DIR__ . '/../../../../data/config.php')){
    require __DIR__ . '/../../../../data/config.php';
    if(empty($config['db']))exit('微擎配置文件错误');
    $database['database']=$config['db']['master']['database'];
    $database['username']=$config['db']['master']['username'];
    $database['password']=$config['db']['master']['password'];
    $database['hostport']=$config['db']['master']['port'];
    $database['hostname']=$config['db']['master']['host'];
}else{

    exit('配置文件目录错误');
}

return $database;


