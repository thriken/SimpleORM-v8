<?php

namespace SimpleORM\Query;

use SimpleORM\Database\Connection;

class Builder
{
    /**
     * 数据库连接实例
     *
     * @var Connection
     */
    protected Connection $connection;

    /**
     * 表名
     *
     * @var string
     */
    protected string $table;

    /**
     * 查询条件
     *
     * @var array
     */
    protected array $wheres = [];

    /**
     * 排序条件
     *
     * @var array
     */
    protected array $orders = [];

    /**
     * 限制数量
     *
     * @var int|null
     */
    protected ?int $limit = null;

    /**
     * 偏移量
     *
     * @var int|null
     */
    protected ?int $offset = null;

    /**
     * 选择的字段
     *
     * @var array
     */
    protected array $selects = ['*'];

    /**
     * JOIN子句
     *
     * @var array
     */
    protected array $joins = [];

    /**
     * GROUP BY子句
     *
     * @var array
     */
    protected array $groups = [];

    /**
     * HAVING子句
     *
     * @var array
     */
    protected array $havings = [];

    /**
     * 构造函数
     *
     * @param Connection $connection
     * @param string $table
     */
    public function __construct(Connection $connection, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    /**
     * 添加WHERE条件
     *
     * @param string $column
     * @param mixed $operator
     * @param mixed $value
     * @return $this
     */
    public function where(string $column, $operator = null, $value = null): static
    {
        // 如果只传了两个参数，则第二个参数是值，操作符为=
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = compact('column', 'operator', 'value');
        return $this;
    }

    /**
     * 添加ORDER BY子句
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->orders[] = compact('column', 'direction');
        return $this;
    }

    /**
     * 添加JOIN子句
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @param string $type
     * @return $this
     */
    public function join(string $table, string $first, string $operator, string $second, string $type = 'inner'): static
    {
        $this->joins[] = compact('table', 'first', 'operator', 'second', 'type');
        return $this;
    }

    /**
     * 添加LEFT JOIN子句
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function leftJoin(string $table, string $first, string $operator, string $second): static
    {
        return $this->join($table, $first, $operator, $second, 'left');
    }

    /**
     * 添加RIGHT JOIN子句
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function rightJoin(string $table, string $first, string $operator, string $second): static
    {
        return $this->join($table, $first, $operator, $second, 'right');
    }

    /**
     * 添加GROUP BY子句
     *
     * @param string $column
     * @return $this
     */
    public function groupBy(string $column): static
    {
        $this->groups[] = $column;
        return $this;
    }

    /**
     * 添加HAVING子句
     *
     * @param string $column
     * @param mixed $operator
     * @param mixed $value
     * @return $this
     */
    public function having(string $column, $operator = null, $value = null): static
    {
        // 如果只传了两个参数，则第二个参数是值，操作符为=
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->havings[] = compact('column', 'operator', 'value');
        return $this;
    }

    /**
     * 设置LIMIT
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * 设置OFFSET
     *
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * 设置SELECT字段
     *
     * @param array $columns
     * @return $this
     */
    public function select(array $columns): static
    {
        $this->selects = $columns;
        return $this;
    }

    /**
     * 获取所有记录
     *
     * @return array
     */
    public function get(): array
    {
        $sql = $this->toSql();
        $bindings = $this->getBindings();
        
        return $this->connection->select($sql, $bindings);
    }

    /**
     * 获取记录数量
     *
     * @return int
     */
    public function count(): int
    {
        $clone = clone $this;
        $clone->selects = ['COUNT(*) as aggregate'];
        $result = $clone->get();
        return (int) ($result[0]['aggregate'] ?? 0);
    }

    /**
     * 获取最大值
     *
     * @param string $column
     * @return mixed
     */
    public function max(string $column)
    {
        $clone = clone $this;
        $clone->selects = ["MAX({$column}) as aggregate"];
        $result = $clone->get();
        return $result[0]['aggregate'] ?? null;
    }

    /**
     * 获取最小值
     *
     * @param string $column
     * @return mixed
     */
    public function min(string $column)
    {
        $clone = clone $this;
        $clone->selects = ["MIN({$column}) as aggregate"];
        $result = $clone->get();
        return $result[0]['aggregate'] ?? null;
    }

    /**
     * 获取平均值
     *
     * @param string $column
     * @return mixed
     */
    public function avg(string $column)
    {
        $clone = clone $this;
        $clone->selects = ["AVG({$column}) as aggregate"];
        $result = $clone->get();
        return $result[0]['aggregate'] ?? null;
    }

    /**
     * 获取总和
     *
     * @param string $column
     * @return mixed
     */
    public function sum(string $column)
    {
        $clone = clone $this;
        $clone->selects = ["SUM({$column}) as aggregate"];
        $result = $clone->get();
        return $result[0]['aggregate'] ?? null;
    }

    /**
     * 获取第一条记录
     *
     * @return object|null
     */
    public function first(): ?object
    {
        $result = $this->limit(1)->get();
        return $result ? (object) $result[0] : null;
    }

    /**
     * 根据主键查找记录
     *
     * @param mixed $id
     * @return object|null
     */
    public function find($id): ?object
    {
        return $this->where('id', $id)->first();
    }

    /**
     * 构建SQL查询语句
     *
     * @return string
     */
    protected function toSql(): string
    {
        $sql = "SELECT " . implode(', ', $this->selects) . " FROM {$this->table}";

        // 添加JOIN子句
        if (!empty($this->joins)) {
            $sql .= " " . $this->buildJoinClause();
        }

        // 添加WHERE子句
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . $this->buildWhereClause();
        }

        // 添加GROUP BY子句
        if (!empty($this->groups)) {
            $sql .= " GROUP BY " . implode(', ', $this->groups);
        }

        // 添加HAVING子句
        if (!empty($this->havings)) {
            $sql .= " HAVING " . $this->buildHavingClause();
        }

        // 添加ORDER BY子句
        if (!empty($this->orders)) {
            $sql .= " ORDER BY " . $this->buildOrderClause();
        }

        // 添加LIMIT子句
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        // 添加OFFSET子句
        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }

    /**
     * 构建WHERE子句
     *
     * @return string
     */
    protected function buildWhereClause(): string
    {
        $clauses = [];
        foreach ($this->wheres as $where) {
            $clauses[] = "{$where['column']} {$where['operator']} ?";
        }
        return implode(' AND ', $clauses);
    }

    /**
     * 构建JOIN子句
     *
     * @return string
     */
    protected function buildJoinClause(): string
    {
        $clauses = [];
        foreach ($this->joins as $join) {
            $clauses[] = strtoupper($join['type']) . " JOIN {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
        }
        return implode(' ', $clauses);
    }

    /**
     * 构建ORDER BY子句
     *
     * @return string
     */
    protected function buildOrderClause(): string
    {
        $clauses = [];
        foreach ($this->orders as $order) {
            $clauses[] = "{$order['column']} {$order['direction']}";
        }
        return implode(', ', $clauses);
    }

    /**
     * 构建HAVING子句
     *
     * @return string
     */
    protected function buildHavingClause(): string
    {
        $clauses = [];
        foreach ($this->havings as $having) {
            $clauses[] = "{$having['column']} {$having['operator']} ?";
        }
        return implode(' AND ', $clauses);
    }

    /**
     * 获取绑定参数
     *
     * @return array
     */
    protected function getBindings(): array
    {
        $bindings = [];
        
        // WHERE子句的绑定参数
        foreach ($this->wheres as $where) {
            $bindings[] = $where['value'];
        }
        
        // HAVING子句的绑定参数
        foreach ($this->havings as $having) {
            $bindings[] = $having['value'];
        }
        
        return $bindings;
    }

    /**
     * 插入记录
     *
     * @param array $data
     * @return string 最后插入的ID
     */
    public function insert(array $data): string
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . 
               implode(', ', array_fill(0, count($values), '?')) . ")";

        return $this->connection->insert($sql, $values);
    }

    /**
     * 更新记录
     *
     * @param array $data
     * @return int 影响的行数
     */
    public function update(array $data): int
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $setClause = implode(', ', array_map(fn($col) => "$col = ?", $columns));
        $sql = "UPDATE {$this->table} SET $setClause";

        // 添加WHERE子句
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . $this->buildWhereClause();
            $values = array_merge($values, $this->getBindings());
        }

        return $this->connection->statement($sql, $values);
    }

    /**
     * 删除记录
     *
     * @return int 影响的行数
     */
    public function delete(): int
    {
        $sql = "DELETE FROM {$this->table}";

        // 添加WHERE子句
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . $this->buildWhereClause();
            $bindings = $this->getBindings();
        } else {
            $bindings = [];
        }

        return $this->connection->statement($sql, $bindings);
    }
}