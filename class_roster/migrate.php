<?php

define('MIGRATION_RUNNER', true);

require_once __DIR__ . '/autoload.php';

use SimpleORM\Database\DatabaseManager;
use SimpleORM\Migration\Migrator;

// 加载配置
$config = require_once __DIR__ . '/config.php';

// 创建数据库管理器
$dbManager = new DatabaseManager($config);

// 创建迁移器
$migrator = new Migrator($dbManager);

echo "=== 数据库迁移工具 ===\n";
echo "1. 运行所有迁移\n";
echo "2. 回滚最后一次迁移\n";
echo "请选择操作 (1 或 2): ";

$input = trim(fgets(STDIN));

switch ($input) {
    case '1':
        echo "正在运行所有迁移...\n";
        try {
            $migrator->run(__DIR__ . '/migrations');
            echo "迁移完成!\n";
        } catch (Exception $e) {
            echo "迁移过程中出错: " . $e->getMessage() . "\n";
        }
        break;
        
    case '2':
        echo "正在回滚最后一次迁移...\n";
        try {
            $migrator->rollback(__DIR__ . '/migrations');
            echo "回滚完成!\n";
        } catch (Exception $e) {
            echo "回滚过程中出错: " . $e->getMessage() . "\n";
        }
        break;
        
    default:
        echo "无效选择\n";
        break;
}