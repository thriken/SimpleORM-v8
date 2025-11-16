<?php

namespace App\Models;

class ClassroomTeacher extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected string $table = 'classroom_teachers';

    /**
     * 是否自动维护时间戳
     *
     * @var bool
     */
    protected bool $timestamps = true;

    /**
     * 定义与班级的属于关系
     *
     * @return \SimpleORM\Model\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    /**
     * 定义与老师的属于关系
     *
     * @return \SimpleORM\Model\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
}