<?php

namespace RecursiveRelationships\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RecursiveRelationships\Traits\HasRecursiveRelationships;

class User extends Model
{
    use SoftDeletes, HasRecursiveRelationships;

    protected $fillable = [
        'name',
    ];

    public function getParentKeyName()
    {
        return 'user_id';
    }
}
