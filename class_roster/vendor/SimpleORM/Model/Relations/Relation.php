<?php

namespace SimpleORM\Model\Relations;

use SimpleORM\Model\Model;

abstract class Relation
{
    /**
     * 父模型实例
     *
     * @var Model
     */
    protected Model $parent;

    /**
     * 关联模型类名
     *
     * @var string
     */
    protected string $related;

    /**
     * 外键字段名
     *
     * @var string
     */
    protected string $foreignKey;

    /**
     * 本地键字段名
     *
     * @var string
     */
    protected string $localKey;

    /**
     * 构造函数
     *
     * @param Model $parent 父模型实例
     * @param string $related 关联模型类名
     * @param string $foreignKey 外键字段名
     * @param string $localKey 本地键字段名
     */
    public function __construct(Model $parent, string $related, string $foreignKey, string $localKey)
    {
        $this->parent = $parent;
        $this->related = $related;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    /**
     * 获取关联模型类名
     *
     * @return string
     */
    public function getRelatedClass(): string
    {
        return $this->related;
    }

    /**
     * 获取外键字段名
     *
     * @return string
     */
    public function getForeignKey(): string
    {
        return $this->foreignKey;
    }

    /**
     * 获取本地键字段名
     *
     * @return string
     */
    public function getLocalKey(): string
    {
        return $this->localKey;
    }

    /**
     * 获取父模型实例
     *
     * @return Model
     */
    public function getParent(): Model
    {
        return $this->parent;
    }

    /**
     * 获取关联模型实例
     *
     * @return Model
     */
    public function getRelated(): Model
    {
        $relatedClass = $this->related;
        return new $relatedClass();
    }

    /**
     * 获取关联结果
     *
     * @return mixed
     */
    abstract public function getResults();

    /**
     * 约束查询
     *
     * @param mixed $query
     * @return mixed
     */
    abstract public function addConstraints($query);
}