<?php

namespace SimpleORM\Model\Relations;

use SimpleORM\Model\Model;

class HasMany extends Relation
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
     * @return array
     */
    public function getResults(): array
    {
        $foreignKeyValue = $this->parent->getAttribute($this->localKey);
        
        if ($foreignKeyValue === null) {
            return [];
        }

        return $this->getRelated()
            ->query()
            ->where($this->foreignKey, $foreignKeyValue)
            ->get();
    }

    /**
     * 约束查询
     *
     * @param mixed $query
     * @return mixed
     */
    public function addConstraints($query)
    {
        // 一对多关系不需要额外约束
        return $query;
    }
}