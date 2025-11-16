<?php

require_once __DIR__ . '/autoload.php';

use SimpleORM\Database\DatabaseManager;

// 加载配置
$config = require_once __DIR__ . '/config.php';

echo "=== 数据库初始化工具 ===\n";
echo "此脚本将帮助您创建数据库和表结构\n\n";

// 获取数据库配置
$dbConfig = $config['connections']['mysql'];
$host = $dbConfig['host'];
$username = $dbConfig['username'];
$password = $dbConfig['password'];
$database = $dbConfig['database'];

echo "数据库配置信息:\n";
echo "- 主机: $host\n";
echo "- 用户名: $username\n";
echo "- 数据库名: $database\n\n";

echo "请确保MySQL服务正在运行，并且用户具有创建数据库的权限。\n";
echo "按回车键继续，或按 Ctrl+C 取消: ";
trim(fgets(STDIN));

try {
    // 创建不带数据库名的连接，用于创建数据库
    $tempConfig = $config;
    $tempConfig['connections']['mysql']['database'] = '';
    $tempDbManager = new DatabaseManager($tempConfig);
    
    // 创建数据库
    $pdo = $tempDbManager->getPdo();
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "数据库 '$database' 创建成功!\n";
    
    // 重新连接到新创建的数据库
    $dbManager = new DatabaseManager($config);
    $pdo = $dbManager->getPdo();
    
    // 创建表结构
    try {
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
        
        echo "表结构创建成功!\n";
        echo "数据库初始化完成!\n";
        echo "现在可以通过Web界面运行迁移: 访问 /migrate.php?action=run\n";
        echo "或者在命令行中运行 'php migrate.php' 来执行迁移操作\n";
    } catch (Exception $e) {
        echo "创建表结构时出错: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "错误: " . $e->getMessage() . "\n";
    echo "请检查数据库配置和权限设置\n";
}