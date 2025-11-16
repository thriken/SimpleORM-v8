<?php

use SimpleORM\Migration\Migration;
use SimpleORM\Database\Connection;

class CreateClassroomTeachersTable extends Migration
{
    /**
     * 运行迁移
     *
     * @param Connection $connection
     * @return void
     */
    public function up(Connection $connection): void
    {
        $this->create($connection, 'classroom_teachers', function ($table) {
            $table->increments('id');
            $table->integer('classroom_id');
            $table->integer('teacher_id');
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
        $this->drop($connection, 'classroom_teachers');
    }
}