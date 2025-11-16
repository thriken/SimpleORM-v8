<?php

namespace App\Models;

class Student extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected string $table = 'students';

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
}