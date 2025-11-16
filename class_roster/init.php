<?php

require_once __DIR__ . '/autoload.php';

use SimpleORM\Database\DatabaseManager;
use SimpleORM\Migration\Migrator;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassroomTeacher;

// 加载配置
$config = require_once __DIR__ . '/config.php';

// 创建数据库管理器
$dbManager = new DatabaseManager($config);

// 设置模型的数据库管理器
Classroom::setDatabaseManager($dbManager);
Student::setDatabaseManager($dbManager);
Teacher::setDatabaseManager($dbManager);
ClassroomTeacher::setDatabaseManager($dbManager);

echo "数据库初始化完成!\n";
echo "使用 'php seed.php' 命令生成示例数据\n";