# 班级花名册管理系统

这是一个使用SimpleORM框架构建的完整示例项目，展示了ORM框架的各种功能。

## 项目结构

```
class_roster/
├── models/                 # 数据模型
├── migrations/             # 数据库迁移文件
├── controllers/            # 控制器（预留）
├── views/                  # 视图文件（预留）
├── public/                 # 公共文件和Web入口
├── config.php             # 数据库配置
├── autoload.php           # 自动加载器
├── init.php               # 数据库初始化脚本
├── seed.php               # 数据填充脚本
└── README.md              # 项目说明
```

## 数据库设计

### 表结构

1. **classrooms** (班级表)
   - id: 主键
   - name: 班级名称
   - grade: 年级
   - timestamps: 时间戳

2. **students** (学生表)
   - id: 主键
   - name: 姓名
   - student_id: 学号 (唯一)
   - classroom_id: 班级ID (外键)
   - gender: 性别
   - birth_date: 出生日期
   - phone: 电话
   - address: 地址
   - timestamps: 时间戳

3. **teachers** (老师表)
   - id: 主键
   - name: 姓名
   - subject: 科目
   - phone: 电话
   - address: 地址
   - timestamps: 时间戳

4. **classroom_teachers** (班级老师关联表)
   - id: 主键
   - classroom_id: 班级ID (外键)
   - teacher_id: 老师ID (外键)
   - timestamps: 时间戳

## 模型关系

- **Classroom** ↔ **Student**: 一对多关系
- **Classroom** ↔ **Teacher**: 多对多关系 (通过中间表classroom_teachers)
- **Student** → **Classroom**: 多对一关系
- **Teacher** → **Classroom**: 多对一关系

## 安装和使用

### 1. 数据库配置

编辑 `config.php` 文件，根据您的实际环境配置数据库连接信息：

```php
// 请根据您的实际环境修改以下配置
return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'host' => 'localhost',
            'database' => 'class_roster',
            'username' => 'your_mysql_username',
            'password' => 'your_mysql_password',
            'charset' => 'utf8mb4',
        ]
    ]
];
```

### 2. 创建数据库

在MySQL中创建数据库：

```sql
CREATE DATABASE class_roster CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. 运行数据库迁移

```bash
php migrate.php
```

### 4. 生成示例数据

```bash
php seed.php
```

这将生成：
- 3个班级
- 90名学生（每个班级30名）
- 10名老师（科目包括语文、数学、英语、音乐、体育）
- 班级与老师的关联关系

## 使用方式

### Web界面

将项目部署到Nginx服务器：

1. 将项目目录 `class_roster` 放到Nginx的web目录下（如 `/var/www/html/`）
2. 配置Nginx虚拟主机，将根目录指向 `class_roster/public`
3. 重启Nginx服务

示例Nginx配置：

```nginx
server {
    listen 80;
    server_name class-roster.local;
    root /var/www/html/class_roster/public;
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    }
}
```

然后在浏览器中访问您的域名或IP地址

## ORM框架功能展示

### 1. 基本CRUD操作

```php
// 创建
$student = Student::create([
    'name' => '张三',
    'student_id' => 'S000001',
    'classroom_id' => 1,
    'gender' => '男',
    'birth_date' => '2005-01-01'
]);

// 读取
$student = Student::find(1);
$students = Student::all();
$students = Student::query()->where('classroom_id', 1)->get();

// 更新
$student->name = '李四';
$student->save();

// 删除
$student->delete();
```

### 2. 查询构建器

```php
// 条件查询
$students = Student::query()
    ->where('gender', '男')
    ->where('birth_date', '>', '2005-01-01')
    ->orderBy('name')
    ->get();

// 聚合查询
$count = Student::query()->where('classroom_id', 1)->count();
$avgAge = Student::query()->avg('age');
```

### 3. 关系映射

```php
// 一对一/一对多关系
$classroom = Classroom::find(1);
$students = $classroom->students; // 获取该班级的所有学生

$student = Student::find(1);
$classroom = $student->classroom; // 获取该学生所在的班级

// 多对多关系
$teacher = Teacher::find(1);
$classrooms = $teacher->classrooms; // 获取该老师任教的所有班级
```

### 4. 数据库迁移

迁移文件位于 `migrations/` 目录下，支持创建表、修改表结构等操作。

## 项目特点

1. **完整的MVC结构**: 虽然简化了控制器和视图，但保持了清晰的代码组织
2. **Web界面操作**: 通过Web界面进行所有操作
3. **丰富的ORM功能展示**: 包含了CRUD、查询构建器、关系映射等核心功能
4. **数据生成工具**: 提供了随机数据生成脚本，便于测试
5. **易于扩展**: 代码结构清晰，便于添加新功能

## 技术栈

- PHP 8.0+
- SimpleORM框架
- MySQL 5.5+
- Nginx
- 原生HTML/CSS (无前端框架依赖)

## 使用说明

1. 确保已安装SimpleORM框架
2. 配置数据库连接
3. 创建数据库 `class_roster`
4. 运行数据库迁移
5. 生成示例数据
6. 配置Nginx并访问Web界面