<?php

require_once __DIR__ . '/autoload.php';

use SimpleORM\Database\DatabaseManager;

// 加载配置
$config = require_once __DIR__ . '/config.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 创建不带数据库名的连接，用于创建数据库
        $tempConfig = $config;
        $tempConfig['connections']['mysql']['database'] = '';
        $tempDbManager = new DatabaseManager($tempConfig);
        
        // 创建数据库
        $pdo = $tempDbManager->getPdo();
        $database = $config['connections']['mysql']['database'];
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // 重新连接到新创建的数据库
        $dbManager = new DatabaseManager($config);
        $pdo = $dbManager->getPdo();
        
        // 创建表结构
        // 创建classrooms表
        $pdo->exec("CREATE TABLE IF NOT EXISTS `classrooms` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `grade` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // 创建students表
        $pdo->exec("CREATE TABLE IF NOT EXISTS `students` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `student_id` VARCHAR(255) NOT NULL UNIQUE,
            `classroom_id` INT NOT NULL,
            `gender` VARCHAR(10) NOT NULL,
            `birth_date` DATE NOT NULL,
            `phone` VARCHAR(255) NULL,
            `address` TEXT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // 创建teachers表
        $pdo->exec("CREATE TABLE IF NOT EXISTS `teachers` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `subject` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(255) NULL,
            `address` TEXT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // 创建classroom_teachers表
        $pdo->exec("CREATE TABLE IF NOT EXISTS `classroom_teachers` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `classroom_id` INT NOT NULL,
            `teacher_id` INT NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $message = "数据库初始化成功！现在可以通过Web界面运行迁移: 访问 /migrate.php?action=run";
        $success = true;
    } catch (Exception $e) {
        $message = "初始化过程中出错: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>数据库初始化</title>
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
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn:hover {
            background: #5a6fd8;
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
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .config-info {
            background: #f1f5f9;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>班级花名册管理系统</h1>
    </header>
    
    <div class="container">
        <h2>数据库初始化</h2>
        
        <?php if ($message): ?>
            <div class="alert <?= $success ? 'alert-success' : 'alert-error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
            
            <?php if ($success): ?>
                <p><a href="/index.php" class="btn">进入系统</a></p>
            <?php endif; ?>
        <?php else: ?>
            <div class="config-info">
                <h3>数据库配置信息</h3>
                <p><strong>主机:</strong> <?= htmlspecialchars($config['connections']['mysql']['host']) ?></p>
                <p><strong>数据库名:</strong> <?= htmlspecialchars($config['connections']['mysql']['database']) ?></p>
                <p><strong>用户名:</strong> <?= htmlspecialchars($config['connections']['mysql']['username']) ?></p>
            </div>
            
            <p>点击下面的按钮初始化数据库和表结构：</p>
            
            <form method="POST">
                <div class="form-group">
                    <button type="submit" class="btn">初始化数据库</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>