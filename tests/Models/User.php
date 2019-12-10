<?php

namespace RecursiveRelationship\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RecursiveRelationship\Traits\HasRecursiveRelationship;

class User extends Model
{
    use SoftDeletes, HasRecursiveRelationship;

    protected $fillable = [
        'name',
    ];

    public function getParentKeyName()
    {
        return 'user_id';
    }
}
