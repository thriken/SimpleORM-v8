<?php

namespace App\Models;

use App\Models\Student;
use App\Models\ClassroomTeacher;

class Classroom extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected string $table = 'classrooms';

    /**
     * 是否自动维护时间戳
     *
     * @var bool
     */
    protected bool $timestamps = true;

    /**
     * 定义与学生的一对多关系
     *
     * @return \SimpleORM\Model\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'classroom_id', 'id');
    }

    /**
     * 定义与老师的多对多关系
     *
     * @return \SimpleORM\Model\Relations\HasMany
     */
    public function teachers()
    {
        return $this->hasMany(ClassroomTeacher::class, 'classroom_id', 'id');
    }
}