# SimpleORM 项目结构

```
SimpleORM/
├── src/
│   ├── Database/
│   │   ├── Connection.php          # 数据库连接类
│   │   └── DatabaseManager.php     # 数据库管理器
│   ├── Model/
│   │   ├── Model.php               # 基础模型类
│   │   └── Relations/              # 关系映射类
│   │       ├── Relation.php        # 关系基类
│   │       ├── HasOne.php          # 一对一关系
│   │       ├── HasMany.php         # 一对多关系
│   │       └── BelongsTo.php       # 属于关系
│   ├── Query/
│   │   └── Builder.php             # 查询构建器
│   ├── Migration/
│   │   ├── Migration.php           # 迁移基类
│   │   ├── Migrator.php            # 迁移管理器
│   │   └── TableSchema.php         # 表结构定义
│   └── helpers.php                 # 辅助函数
├── examples/
│   ├── User.php                    # 用户模型示例
│   ├── Post.php                    # 文章模型示例
│   └── example.php                 # 使用示例
├── migrations/
│   └── 2025_11_14_000001_create_users_table.php  # 示例迁移文件
├── tests/
│   └── test_basic.php              # 基础测试脚本
├── README.md                       # 项目说明文档
├── composer.json                   # Composer配置文件
└── autoload.php                    # 自动加载文件
```

## 核心组件说明

### 1. 数据库层 (Database)
- **Connection**: 负责实际的数据库连接和查询执行
- **DatabaseManager**: 管理多个数据库连接实例

### 2. 模型层 (Model)
- **Model**: 所有数据模型的基类，提供ORM核心功能
- **Relations**: 实现模型间的关系映射

### 3. 查询层 (Query)
- **Builder**: 提供链式调用的查询构建器

### 4. 迁移系统 (Migration)
- **Migration**: 迁移文件的基类
- **Migrator**: 迁移执行器
- **TableSchema**: 表结构定义器

## 主要特性

1. **数据库连接管理**: 支持单例模式和多个数据库连接
2. **Active Record模式**: 模型对象直接映射数据库记录
3. **查询构建器**: 提供链式调用的查询接口
4. **关系映射**: 支持一对一、一对多、多对多关系
5. **数据库迁移**: 支持数据库结构版本控制
6. **自动加载**: 符合PSR-4标准的自动加载机制