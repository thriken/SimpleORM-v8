<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SimpleORM\Model\Model;

class User extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected string $table = 'users';

    /**
     * 是否自动维护时间戳
     *
     * @var bool
     */
    protected bool $timestamps = true;
}