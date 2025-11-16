<?php

require_once __DIR__ . '/../autoload.php';

use SimpleORM\Database\Connection;
use SimpleORM\Database\DatabaseManager;

echo "=== SimpleORM 基础测试 ===\n\n";

// 测试类是否能正确加载
echo "1. 测试类加载...\n";
$classes = [
    'SimpleORM\Database\Connection',
    'SimpleORM\Database\DatabaseManager',
    'SimpleORM\Model\Model',
    'SimpleORM\Query\Builder',
    'SimpleORM\Migration\Migration',
    'SimpleORM\Migration\Migrator',
    'SimpleORM\Migration\TableSchema'
];

$allLoaded = true;
foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "  ✓ $class\n";
    } else {
        echo "  ✗ $class\n";
        $allLoaded = false;
    }
}

if ($allLoaded) {
    echo "  所有类加载成功!\n\n";
} else {
    echo "  部分类加载失败!\n\n";
}

// 测试辅助函数
echo "2. 测试辅助函数...\n";
if (function_exists('class_basename')) {
    $basename = class_basename('SimpleORM\Model\Model');
    if ($basename === 'Model') {
        echo "  ✓ class_basename 函数工作正常\n";
    } else {
        echo "  ✗ class_basename 函数返回错误结果: $basename\n";
    }
} else {
    echo "  ✗ class_basename 函数未定义\n";
}

echo "\n=== 测试完成 ===\n";