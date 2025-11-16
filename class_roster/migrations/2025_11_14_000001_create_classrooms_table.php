<?php

use SimpleORM\Migration\Migration;
use SimpleORM\Database\Connection;

class CreateClassroomsTable extends Migration
{
    /**
     * 运行迁移
     *
     * @param Connection $connection
     * @return void
     */
    public function up(Connection $connection): void
    {
        $this->create($connection, 'classrooms', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('grade');
            $table->timestamps();
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
        $this->drop($connection, 'classrooms');
    }
}