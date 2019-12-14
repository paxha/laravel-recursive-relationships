<?php

namespace RecursiveRelationships\Traits;

use Illuminate\Database\Eloquent\Builder;
use RecursiveRelationships\Relations\HasManySiblings;

trait HasRecursiveRelationships
{
    private $descendents = [];
    private $ancestors = [];

    public function getParentKeyName()
    {
        return 'parent_id';
    }

    public function children()
    {
        return $this->/* @scrutinizer ignore-call */ hasMany(self::class, $this->getParentKeyName());
    }

    public function nestedChildren()
    {
        return $this->children()->with('nestedChildren');
    }

    public function parent()
    {
        return $this->/* @scrutinizer ignore-call */ belongsTo(self::class, $this->getParentKeyName());
    }

    public function nestedParents()
    {
        return $this->/* @scrutinizer ignore-call */ belongsTo(self::class, $this->getParentKeyName())->with('nestedParents');
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

    public function descendents()
    {
        if ($this->children) {
            $this->collectDescendents($this->children);
        }
    }

    private function collectDescendents($children)
    {
        foreach ($children as $child) {
            $this->descendents[] = $child;
            if ($child->children) {
                $this->collectDescendents($child->children);
            }
        }
    }

    public function ancestors()
    {
        if ($this->parent) {
            $this->collectAncestors($this->parent);
        }

        return collect($this->ancestors);
    }

    private function collectAncestors($parent)
    {
        $this->ancestors[] = $parent;
        if ($parent->parent) {
            $this->collectAncestors($parent->parent);
        }
    }
}
