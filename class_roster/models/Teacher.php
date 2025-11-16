<?php

namespace App\Models;

use App\Models\ClassroomTeacher;

class Teacher extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected string $table = 'teachers';

    /**
     * 是否自动维护时间戳
     *
     * @var bool
     */
    protected bool $timestamps = true;

    /**
     * 定义与班级的多对多关系
     *
     * @return \SimpleORM\Model\Relations\HasMany
     */
    public function classrooms()
    {
        return $this->hasMany(ClassroomTeacher::class, 'teacher_id', 'id');
    }
}