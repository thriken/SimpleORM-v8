<?php

use SimpleORM\Migration\Migration;
use SimpleORM\Database\Connection;

class CreateUsersTable extends Migration
{
    /**
     * 运行迁移
     *
     * @param Connection $connection
     * @return void
     */
    public function up(Connection $connection): void
    {
        $this->create($connection, 'users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * 回滚迁移
     *
     * @param Connection $connection
     * @return void
     */
    public function down(Connection $connection): void
    {
        $this->drop($connection, 'users');
    }
}