<?php

use SimpleORM\Migration\Migration;
use SimpleORM\Database\Connection;

class CreateStudentsTable extends Migration
{
    /**
     * 运行迁移
     *
     * @param Connection $connection
     * @return void
     */
    public function up(Connection $connection): void
    {
        $this->create($connection, 'students', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('student_id')->unique();
            $table->integer('classroom_id');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
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
        $this->drop($connection, 'students');
    }
}