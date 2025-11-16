<?php

define('MIGRATION_RUNNER', true);

use SimpleORM\Database\DatabaseManager;
use SimpleORM\Migration\Migrator;

// 加载配置
$config = require_once __DIR__ . '/../autoload.php';

// 创建数据库管理器
$dbManager = new DatabaseManager($config);

// 创建迁移器
$migrator = new Migrator($dbManager);

$message = '';
$messageType = '';

// 获取操作参数
$action = $_GET['action'] ?? '';

// 处理迁移操作
if ($action) {
    try {
        switch ($action) {
            case 'run':
                $migrator->run(__DIR__ . '/../migrations');
                $message = '迁移完成!';
                $messageType = 'success';
                break;
                
            case 'rollback':
                $migrator->rollback(__DIR__ . '/../migrations');
                $message = '回滚完成!';
                $messageType = 'success';
                break;
                
            default:
                $message = '无效操作';
                $messageType = 'error';
                break;
        }
    } catch (Exception $e) {
        $message = '操作失败: ' . $e->getMessage();
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>数据库迁移工具</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            margin: 0;
            font-size: 2rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #e6fffa;
            border-left: 4px solid #00b894;
        }
        .alert-error {
            background: #fff5f5;
            border-left: 4px solid #e53e3e;
        }
        .alert-warning {
            background: #fffbeb;
            border-left: 4px solid #d69e2e;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin: 0.5rem;
        }
        .btn:hover {
            background: #5a6fd8;
            text-decoration: none;
        }
        .btn-secondary {
            background: #718096;
        }
        .btn-secondary:hover {
            background: #4a5568;
        }
        .btn-danger {
            background: #e53e3e;
        }
        .btn-danger:hover {
            background: #c53030;
        }
    </style>
</head>
<body>
    <header>
        <h1>班级花名册管理系统</h1>
    </header>
    
    <div class="container">
        <h2>数据库迁移工具</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <p>您可以使用以下选项来管理数据库迁移：</p>
        
        <p><a href="?action=run" class="btn">运行所有迁移</a></p>
        <p><a href="?action=rollback" class="btn btn-danger">回滚最后一次迁移</a></p>
        <p><a href="<?= SITE_ROOT ?>/index.php" class="btn btn-secondary">返回首页</a></p>
    </div>
</body>
</html>