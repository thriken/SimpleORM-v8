<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SimpleORM\Database\DatabaseManager;
use SimpleORM\Migration\Migrator;

// 数据库配置
$config = [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'host' => 'localhost',
            'database' => 'test_db',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ]
    ]
];

// 创建数据库管理器
$dbManager = new DatabaseManager($config);

// 设置模型的数据库管理器
User::setDatabaseManager($dbManager);
Post::setDatabaseManager($dbManager);

// 创建迁移器并运行迁移
$migrator = new Migrator($dbManager);
// $migrator->run(__DIR__ . '/../migrations');

// 使用示例
echo "=== SimpleORM 使用示例 ===\n\n";

// 1. 创建用户
echo "1. 创建用户...\n";
$user = User::create([
    'name' => '张三',
    'email' => 'zhangsan@example.com',
    'password' => password_hash('123456', PASSWORD_DEFAULT)
]);

echo "创建的用户ID: " . $user->id . "\n\n";

// 2. 查询用户
echo "2. 查询用户...\n";
$users = User::all();
echo "所有用户数量: " . count($users) . "\n";

$user = User::find(1);
if ($user) {
    echo "找到用户: " . $user->name . " (" . $user->email . ")\n";
}

// 3. 更新用户
echo "\n3. 更新用户...\n";
if ($user) {
    $user->name = '张三丰';
    $user->save();
    echo "用户更新成功\n";
}

// 4. 使用查询构建器
echo "\n4. 使用查询构建器...\n";
$users = User::query()
    ->where('name', 'like', '%张%')
    ->orderBy('created_at', 'desc')
    ->get();

echo "查询到 " . count($users) . " 个用户名字包含'张'的用户\n";

// 5. 关系映射示例
echo "\n5. 关系映射示例...\n";
// 假设我们有Post模型
// $posts = Post::with('user')->get();
// foreach ($posts as $post) {
//     echo "文章: " . $post->title . " 作者: " . $post->user->name . "\n";
// }

// 6. 聚合查询
echo "\n6. 聚合查询...\n";
$userCount = User::query()->count();
echo "用户总数: " . $userCount . "\n";

echo "\n=== 示例结束 ===\n";