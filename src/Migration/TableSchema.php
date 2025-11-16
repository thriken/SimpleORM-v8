<?php

namespace SimpleORM\Migration;

class TableSchema
{
    /**
     * 表名
     *
     * @var string
     */
    protected string $table;

    /**
     * 字段定义
     *
     * @var array
     */
    protected array $columns = [];

    /**
     * 构造函数
     *
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * 添加自增主键字段
     *
     * @param string $name 字段名
     * @return $this
     */
    public function increments(string $name): static
    {
        $this->columns[] = [
            'name' => $name,
            'type' => 'INT',
            'autoIncrement' => true,
            'primaryKey' => true,
            'nullable' => false
        ];
        return $this;
    }

    /**
     * 添加字符串字段
     *
     * @param string $name 字段名
     * @param int $length 长度
     * @return $this
     */
    public function string(string $name, int $length = 255): static
    {
        $this->columns[] = [
            'name' => $name,
            'type' => "VARCHAR($length)",
            'nullable' => false
        ];
        return $this;
    }

    /**
     * 设置字段为唯一
     *
     * @return $this
     */
    public function unique(): static
    {
        $lastIndex = count($this->columns) - 1;
        if ($lastIndex >= 0) {
            $this->columns[$lastIndex]['unique'] = true;
        }
        return $this;
    }

    /**
     * 添加文本字段
     *
     * @param string $name 字段名
     * @return $this
     */
    public function text(string $name): static
    {
        $this->columns[] = [
            'name' => $name,
            'type' => 'TEXT',
            'nullable' => false
        ];
        return $this;
    }

    /**
     * 添加整数字段
     *
     * @param string $name 字段名
     * @return $this
     */
    public function integer(string $name): static
    {
        $this->columns[] = [
            'name' => $name,
            'type' => 'INT',
            'nullable' => false
        ];
        return $this;
    }

    /**
     * 添加时间戳字段
     *
     * @param string $name 字段名
     * @return $this
     */
    public function timestamp(string $name): static
    {
        $this->columns[] = [
            'name' => $name,
            'type' => 'TIMESTAMP',
            'nullable' => true
        ];
        return $this;
    }

    /**
     * 设置字段为可为空
     *
     * @return $this
     */
    public function nullable(): static
    {
        $lastIndex = count($this->columns) - 1;
        if ($lastIndex >= 0) {
            $this->columns[$lastIndex]['nullable'] = true;
        }
        return $this;
    }

    /**
     * 设置默认值
     *
     * @param mixed $value
     * @return $this
     */
    public function default($value): static
    {
        $lastIndex = count($this->columns) - 1;
        if ($lastIndex >= 0) {
            $this->columns[$lastIndex]['default'] = $value;
        }
        return $this;
    }

    /**
     * 添加软删除字段
     *
     * @return $this
     */
    public function softDeletes(): static
    {
        return $this->timestamp('deleted_at')->nullable();
    }

    /**
     * 添加时间戳字段
     *
     * @return $this
     */
    public function timestamps(): static
    {
        $this->timestamp('created_at')->nullable();
        $this->timestamp('updated_at')->nullable();
        return $this;
    }

    /**
     * 生成创建表的SQL语句
     *
     * @return string
     */
    public function toSql(): string
    {
        $columns = [];
        $primaryKeys = [];

        foreach ($this->columns as $column) {
            $definition = $this->getColumnDefinition($column);
            $columns[] = $definition;

            if (isset($column['primaryKey']) && $column['primaryKey']) {
                $primaryKeys[] = $column['name'];
            }
        }

        $sql = "CREATE TABLE {$this->table} (" . implode(', ', $columns);

        if (!empty($primaryKeys)) {
            $sql .= ", PRIMARY KEY (" . implode(', ', $primaryKeys) . ")";
        }

        // 添加唯一约束
        $uniqueConstraints = [];
        foreach ($this->columns as $column) {
            if (isset($column['unique']) && $column['unique']) {
                $uniqueConstraints[] = "UNIQUE ({$column['name']})";
            }
        }
        
        if (!empty($uniqueConstraints)) {
            $sql .= ", " . implode(', ', $uniqueConstraints);
        }

        $sql .= ")";

        return $sql;
    }

    /**
     * 生成修改表的SQL语句
     *
     * @return string
     */
    public function toAlterSql(): string
    {
        $columns = [];
        foreach ($this->columns as $column) {
            $definition = $this->getColumnDefinition($column);
            $columns[] = "ADD COLUMN $definition";
        }

        return "ALTER TABLE {$this->table} " . implode(', ', $columns);
    }

    /**
     * 获取字段定义
     *
     * @param array $column
     * @return string
     */
    protected function getColumnDefinition(array $column): string
    {
        $definition = "{$column['name']} {$column['type']}";

        if (isset($column['nullable']) && !$column['nullable']) {
            $definition .= " NOT NULL";
        }

        if (isset($column['autoIncrement']) && $column['autoIncrement']) {
            $definition .= " AUTO_INCREMENT";
        }

        if (isset($column['default'])) {
            $default = $column['default'];
            if (is_string($default)) {
                $default = "'$default'";
            }
            $definition .= " DEFAULT $default";
        }

        return $definition;
    }
}