<?php

namespace SimpleORM\Model\Relations;

use SimpleORM\Model\Model;

class HasOne extends Relation
{
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
        parent::__construct($parent, $related, $foreignKey, $localKey);
    }

    /**
     * 获取关联结果
     *
     * @return Model|null
     */
    public function getResults(): ?Model
    {
        $foreignKeyValue = $this->parent->getAttribute($this->localKey);
        
        if ($foreignKeyValue === null) {
            return null;
        }

        return $this->getRelated()
            ->query()
            ->where($this->foreignKey, $foreignKeyValue)
            ->first();
    }

    /**
     * 约束查询
     *
     * @param mixed $query
     * @return mixed
     */
    public function addConstraints($query)
    {
        // 一对一关系不需要额外约束
        return $query;
    }
}