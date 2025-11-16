<?php

namespace SimpleORM\Model\Relations;

use SimpleORM\Model\Model;

class BelongsTo extends Relation
{
    /**
     * 构造函数
     *
     * @param Model $parent 父模型实例
     * @param string $related 关联模型类名
     * @param string $foreignKey 外键字段名
     * @param string $ownerKey 拥有者键字段名
     */
    public function __construct(Model $parent, string $related, string $foreignKey, string $ownerKey)
    {
        parent::__construct($parent, $related, $foreignKey, $ownerKey);
    }

    /**
     * 获取关联结果
     *
     * @return Model|null
     */
    public function getResults(): ?Model
    {
        $foreignKeyValue = $this->parent->getAttribute($this->foreignKey);
        
        if ($foreignKeyValue === null) {
            return null;
        }

        return $this->getRelated()
            ->query()
            ->where($this->localKey, $foreignKeyValue)
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
        // 属于关系不需要额外约束
        return $query;
    }
}