<?php

namespace RecursiveRelationship\Traits;

use Illuminate\Database\Eloquent\Builder;
use RecursiveRelationship\Relations\HasManySiblings;

trait HasRecursiveRelationship
{
    public function getParentKeyName()
    {
        return 'parent_id';
    }

    public function children()
    {
        return $this->/** @scrutinizer ignore-call */ hasMany(self::class, $this->getParentKeyName());
    }

    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    public function parent()
    {
        return $this->/* @scrutinizer ignore-call */ belongsTo(self::class, $this->getParentKeyName());
    }

    public function ancestor()
    {
        return $this->/* @scrutinizer ignore-call */ belongsTo(self::class, $this->getParentKeyName())->with('ancestor');
    }

    public function siblings()
    {
        return new HasManySiblings((new self())->/* @scrutinizer ignore-call */ newQuery(), /* @scrutinizer ignore-type */ $this, $this->getParentKeyName(), $this->/* @scrutinizer ignore-call */ getKeyName());
    }

    public function scopeHasChildren(Builder $query)
    {
        $keys = (new self())->/* @scrutinizer ignore-call */ newQuery()
            ->select($this->getParentKeyName())
            ->hasParent();

        return $query->whereIn($this->getKeyName(), $keys);
    }

    public function scopeHasParent(Builder $query)
    {
        return $query->whereNotNull($this->getParentKeyName());
    }

    public function scopeLeaf(Builder $query)
    {
        $keys = (new self())->/* @scrutinizer ignore-call */ newQuery()
            ->select($this->getParentKeyName())
            ->hasParent();

        return $query->whereNotIn($this->getKeyName(), $keys);
    }

    public function scopeRoot(Builder $query)
    {
        return $query->whereNull($this->getParentKeyName());
    }
}
