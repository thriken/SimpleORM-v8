<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SimpleORM\Model\Model;

class Post extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected string $table = 'posts';

    /**
     * 是否自动维护时间戳
     *
     * @var bool
     */
    protected bool $timestamps = true;

    /**
     * 定义与User模型的一对多关系
     *
     * @return \SimpleORM\Model\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}