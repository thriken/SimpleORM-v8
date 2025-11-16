<?php

// 站点配置
if (!defined('SITE_ROOT')) {
    // 根据您的部署情况修改此路径
    // 例如：如果部署在 https://mangosweb/class_roster/public/，则设置为 '/class_roster/public'
    define('SITE_ROOT', '/class_roster/public');
}

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}

// 数据库配置
// 请根据您的实际环境修改以下配置
return [
    'site_root' => SITE_ROOT,
    'project_root' => PROJECT_ROOT,
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'host' => '127.127.126.3',
            'database' => 'test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ]
    ]
];