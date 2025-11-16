<?php

namespace SimpleORM\Model;

use SimpleORM\Database\DatabaseManager;
use SimpleORM\Query\Builder;
use SimpleORM\Model\Relations\HasOne;
use SimpleORM\Model\Relations\HasMany;
use SimpleORM\Model\Relations\BelongsTo;
use RuntimeException;

require_once __DIR__ . '/../helpers.php';

abstract class Model
{
    /**
     * 数据库表名
     *
     * @var string
     */
    protected string $table;

    /**
     * 主键字段名
     *
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * 是否自动维护时间戳
     *
     * @var bool
     */
    protected bool $timestamps = true;

    /**
     * 创建时间字段名
     *
     * @var string
     */
    protected string $createdAtColumn = 'created_at';

    /**
     * 更新时间字段名
     *
     * @var string
     */
    protected string $updatedAtColumn = 'updated_at';

    /**
     * 模型属性
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * 原始属性（用于判断哪些属性被修改）
     *
     * @var array
     */
    protected array $original = [];

    /**
     * 数据库管理器实例
     *
     * @var DatabaseManager|null
     */
    protected static ?DatabaseManager $dbManager = null;

    /**
     * 设置数据库管理器实例
     *
     * @param DatabaseManager $dbManager
     * @return void
     */
    public static function setDatabaseManager(DatabaseManager $dbManager): void
    {
        static::$dbManager = $dbManager;
    }

    /**
     * 获取数据库管理器实例
     *
     * @return DatabaseManager
     */
    public static function getDatabaseManager(): DatabaseManager
    {
        if (static::$dbManager === null) {
            throw new RuntimeException('Database manager not set');
        }

        return static::$dbManager;
    }

    /**
     * 获取数据库连接
     *
     * @return \SimpleORM\Database\Connection
     */
    protected static function getConnection()
    {
        return static::getDatabaseManager()->connection();
    }

    /**
     * 获取查询构建器实例
     *
     * @return Builder
     */
    public static function query(): Builder
    {
        return new Builder(static::getConnection(), static::getTable());
    }

    /**
     * 魔术方法：静态调用查询构建器方法
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters)
    {
        return static::query()->{$method}(...$parameters);
    }

    /**
     * 魔术方法：动态调用查询构建器方法
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return static::query()->{$method}(...$parameters);
    }

    /**
     * 获取表名
     *
     * @return string
     */
    public static function getTable(): string
    {
        $instance = new static();
        return $instance->table ?: strtolower(class_basename(static::class)) . 's';
    }

    /**
     * 查找记录
     *
     * @param mixed $id
     * @return static|null
     */
    public static function find($id): ?static
    {
        $result = static::query()->where(static::getPrimaryKey(), $id)->first();
        
        if ($result === null) {
            return null;
        }
        
        // 将stdClass对象转换为模型实例
        $instance = new static();
        foreach ($result as $key => $value) {
            $instance->setAttribute($key, $value);
        }
        $instance->syncOriginal();
        
        return $instance;
    }

    /**
     * 获取所有记录
     *
     * @return array
     */
    public static function all(): array
    {
        return static::query()->get();
    }

    /**
     * 获取主键字段名
     *
     * @return string
     */
    public static function getPrimaryKey(): string
    {
        $instance = new static();
        return $instance->primaryKey;
    }

    /**
     * 创建新记录
     *
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes): static
    {
        $instance = new static();
        $instance->fill($attributes);
        $instance->save();
        return $instance;
    }

    /**
     * 填充模型属性
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * 设置属性值
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute(string $key, $value): static
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * 获取属性值
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * 魔术方法：获取属性值
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    /**
     * 魔术方法：设置属性值
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    /**
     * 保存模型
     *
     * @return bool
     */
    public function save(): bool
    {
        if ($this->exists()) {
            return $this->update();
        }

        return $this->insert();
    }

    /**
     * 判断模型是否已存在数据库中
     *
     * @return bool
     */
    public function exists(): bool
    {
        return isset($this->attributes[$this->primaryKey]) && 
               isset($this->original[$this->primaryKey]);
    }

    /**
     * 插入新记录
     *
     * @return bool
     */
    protected function insert(): bool
    {
        if ($this->timestamps) {
            $now = date('Y-m-d H:i:s');
            $this->setAttribute($this->createdAtColumn, $now);
            $this->setAttribute($this->updatedAtColumn, $now);
        }

        $attributes = $this->attributes;
        $columns = array_keys($attributes);
        $values = array_values($attributes);

        $sql = "INSERT INTO {$this->getTable()} (" . implode(', ', $columns) . ") VALUES (" . 
               implode(', ', array_fill(0, count($values), '?')) . ")";

        $id = static::getConnection()->insert($sql, $values);
        $this->setAttribute($this->primaryKey, $id);
        $this->syncOriginal();

        return true;
    }

    /**
     * 更新记录
     *
     * @return bool
     */
    protected function update(): bool
    {
        if (!$this->isDirty()) {
            return true;
        }

        if ($this->timestamps) {
            $this->setAttribute($this->updatedAtColumn, date('Y-m-d H:i:s'));
        }

        $dirtyAttributes = $this->getDirty();
        unset($dirtyAttributes[$this->primaryKey]);

        $columns = array_keys($dirtyAttributes);
        $values = array_values($dirtyAttributes);

        $setClause = implode(', ', array_map(fn($col) => "$col = ?", $columns));
        $sql = "UPDATE {$this->getTable()} SET $setClause WHERE {$this->primaryKey} = ?";

        // 添加主键值到参数数组末尾
        $values[] = $this->getAttribute($this->primaryKey);

        $result = static::getConnection()->statement($sql, $values);
        $this->syncOriginal();

        return $result > 0;
    }

    /**
     * 删除记录
     *
     * @return bool
     */
    public function delete(): bool
    {
        if (!$this->exists()) {
            return false;
        }

        $sql = "DELETE FROM {$this->getTable()} WHERE {$this->primaryKey} = ?";
        $result = static::getConnection()->statement($sql, [$this->getAttribute($this->primaryKey)]);

        // 清空属性
        $this->attributes = [];
        $this->original = [];

        return $result > 0;
    }

    /**
     * 同步原始属性
     *
     * @return void
     */
    protected function syncOriginal(): void
    {
        $this->original = $this->attributes;
    }

    /**
     * 判断是否有属性被修改
     *
     * @return bool
     */
    public function isDirty(): bool
    {
        return $this->attributes != $this->original;
    }

    /**
     * 获取被修改的属性
     *
     * @return array
     */
    public function getDirty(): array
    {
        return array_diff_assoc($this->attributes, $this->original);
    }

    /**
     * 转换为数组
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * 定义一对一关系
     *
     * @param string $related 关联模型类名
     * @param string|null $foreignKey 外键字段名
     * @param string|null $localKey 本地键字段名
     * @return HasOne
     */
    public function hasOne(string $related, ?string $foreignKey = null, ?string $localKey = null): HasOne
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey = $localKey ?: $this->primaryKey;

        return new HasOne($this, $related, $foreignKey, $localKey);
    }

    /**
     * 定义一对多关系
     *
     * @param string $related 关联模型类名
     * @param string|null $foreignKey 外键字段名
     * @param string|null $localKey 本地键字段名
     * @return HasMany
     */
    public function hasMany(string $related, ?string $foreignKey = null, ?string $localKey = null): HasMany
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey = $localKey ?: $this->primaryKey;

        return new HasMany($this, $related, $foreignKey, $localKey);
    }

    /**
     * 定义属于关系
     *
     * @param string $related 关联模型类名
     * @param string|null $foreignKey 外键字段名
     * @param string|null $ownerKey 拥有者键字段名
     * @return BelongsTo
     */
    public function belongsTo(string $related, ?string $foreignKey = null, ?string $ownerKey = null): BelongsTo
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $ownerKey = $ownerKey ?: (new $related())->primaryKey;

        return new BelongsTo($this, $related, $foreignKey, $ownerKey);
    }

    /**
     * 获取外键字段名
     *
     * @return string
     */
    protected function getForeignKey(): string
    {
        return strtolower(class_basename(static::class)) . '_id';
    }
}