<?php

use SimpleORM\Migration\Migration;
use SimpleORM\Database\Connection;

class TestMigration extends Migration
{
    /**
     * 运行迁移
     *
     * @param Connection $connection
     * @return void
     */
    public function up(Connection $connection): void
    {
        // 这是一个测试迁移，不执行任何操作
        echo "测试迁移执行成功\n";
    }

    /**
     * 回滚迁移
     *
     * @param Connection $connection
     * @return void
     */
    public function down(Connection $connection): void
    {
        // 回滚测试迁移，不执行任何操作
        echo "测试迁移回滚成功\n";
    }
}