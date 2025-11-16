<?php

// 加载配置
$config = require_once __DIR__ . '/config.php';

// 注册SimpleORM命名空间
spl_autoload_register(function ($class) {
    // SimpleORM命名空间前缀
    $prefix = 'SimpleORM\\';
    
    // 命名空间前缀的长度
    $len = strlen($prefix);
    
    // 如果类名不以命名空间前缀开头，则不处理
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // 获取相对类名
    $relativeClass = substr($class, $len);
    
    // 将命名空间分隔符替换为目录分隔符
    $file = __DIR__ . '/vendor/SimpleORM/' . str_replace('\\', '/', $relativeClass) . '.php';
    
    // 如果文件存在，则包含它
    if (file_exists($file)) {
        require $file;
    }
});

// 注册项目命名空间
spl_autoload_register(function ($class) {
    // 项目命名空间前缀
    $prefix = 'App\\';
    
    // 命名空间前缀的长度
    $len = strlen($prefix);
    
    // 如果类名不以命名空间前缀开头，则不处理
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // 获取相对类名
    $relativeClass = substr($class, $len);
    
    // 将命名空间分隔符替换为目录分隔符
    $file = __DIR__ . '/' . str_replace('\\', '/', $relativeClass) . '.php';
    
    // 如果文件存在，则包含它
    if (file_exists($file)) {
        require $file;
    }
});

// 返回配置数组
return $config;