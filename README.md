# SimpleORM

一个轻量级的PHP ORM框架，适用于MySQL 5.5+

## 特性

- 简单易用的数据库操作接口
- 查询构建器
- 模型关系映射
- 数据库迁移支持
- 支持MySQL 5.5+

## 环境要求

- PHP 8.0+
- MySQL 5.5+
- PDO扩展

## 安装

```bash
# 克隆项目
git clone <repository-url>

# 进入项目目录
cd simple-orm
```

## 快速开始

### 1. 配置数据库

```php
use SimpleORM\Database\DatabaseManager;

// 数据库配置
$config = [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'host' => 'localhost',
            'database' => 'your_database',
            'username' => 'your_username',
            'password' => 'your_password',
            'charset' => 'utf8mb4',
        ]
    ]
];

// 创建数据库管理器
$dbManager = new DatabaseManager($config);
```

### 2. 创建模型

```php
use SimpleORM\Model\Model;

class User extends Model
{
    protected string $table = 'users';
    protected bool $timestamps = true;
}

// 设置模型的数据库管理器
User::setDatabaseManager($dbManager);
```

### 3. 基本操作

```php
// 创建记录
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// 查询所有记录
$users = User::all();

// 根据ID查找
$user = User::find(1);

// 条件查询
$users = User::query()
    ->where('name', 'John')
    ->orderBy('created_at', 'desc')
    ->get();

// 更新记录
$user->name = 'Jane Doe';
$user->save();

// 删除记录
$user->delete();
```

### 4. 关系映射

```php
class Post extends Model
{
    protected string $table = 'posts';
    
    // 定义与User模型的关系
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// 使用关系
$post = Post::find(1);
echo $post->user->name;
```

### 5. 数据库迁移

```php
use SimpleORM\Migration\Migrator;

// 创建迁移器
$migrator = new Migrator($dbManager);

// 运行迁移
$migrator->run(__DIR__ . '/migrations');

// 回滚迁移
$migrator->rollback(__DIR__ . '/migrations');
```

## API 参考

### 模型方法

- `find($id)`: 根据主键查找记录
- `all()`: 获取所有记录
- `create($attributes)`: 创建新记录
- `save()`: 保存模型
- `delete()`: 删除记录

### 查询构建器方法

- `where($column, $operator, $value)`: 添加WHERE条件
- `orderBy($column, $direction)`: 添加排序
- `limit($limit)`: 限制结果数量
- `select($columns)`: 选择特定字段
- `count()`: 获取记录数量
- `max($column)`: 获取最大值
- `min($column)`: 获取最小值
- `avg($column)`: 获取平均值
- `sum($column)`: 获取总和

### 关系方法

- `hasOne($related, $foreignKey, $localKey)`: 一对一关系
- `hasMany($related, $foreignKey, $localKey)`: 一对多关系
- `belongsTo($related, $foreignKey, $ownerKey)`: 属于关系