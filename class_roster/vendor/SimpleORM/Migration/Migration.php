<?php

namespace SimpleORM\Migration;

use SimpleORM\Database\Connection;

abstract class Migration
{
    /**
     * 运行迁移
     *
     * @param Connection $connection
     * @return void
     */
    abstract public function up(Connection $connection): void;

    /**
     * 回滚迁移
     *
     * @param Connection $connection
     * @return void
     */
    abstract public function down(Connection $connection): void;

    /**
     * 创建表
     *
     * @param Connection $connection
     * @param string $table 表名
     * @param callable $callback 回调函数，用于定义表结构
     * @return void
     */
    protected function create(Connection $connection, string $table, callable $callback): void
    {
        $schema = new TableSchema($table);
        $callback($schema);
        
        $sql = $schema->toSql();
        $connection->statement($sql);
    }

    /**
     * 修改表
     *
     * @param Connection $connection
     * @param string $table 表名
     * @param callable $callback 回调函数，用于定义表结构修改
     * @return void
     */
    protected function table(Connection $connection, string $table, callable $callback): void
    {
        $schema = new TableSchema($table);
        $callback($schema);
        
        $sql = $schema->toAlterSql();
        $connection->statement($sql);
    }

    /**
     * 删除表
     *
     * @param Connection $connection
     * @param string $table 表名
     * @return void
     */
    protected function drop(Connection $connection, string $table): void
    {
        $sql = "DROP TABLE IF EXISTS {$table}";
        $connection->statement($sql);
    }
}