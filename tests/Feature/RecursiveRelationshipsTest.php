<?php

namespace RecursiveRelationships\Tests\Feature;

use RecursiveRelationships\Tests\Models\User;
use RecursiveRelationships\Tests\TestCase;

class RecursiveRelationshipsTest extends TestCase
{
    public function testChildren()
    {
        $user = User::all()->first();

        $this->assertCount(2, $user->children);
    }

    public function testRootHasParent()
    {
        $user = User::all()->first();

        $this->assertEmpty($user->parent);
    }

    public function testChildHasParent()
    {
        $user = User::all()->last();

        $this->assertIsObject($user->parent);
    }

    public function testAncestors()
    {
        $user = User::where('user_id', '!=', null)->get()->last();

        $this->assertCount(2, $user->ancestors());
    }

    public function testSiblings()
    {
        $user = User::all()->last();

        $this->assertCount(2, $user->siblings);
    }

    public function testHasChildren()
    {
        $this->assertCount(6, User::hasChildren()->get());
    }

    public function testHasParent()
    {
        $this->assertCount(16, User::hasParent()->get());
    }

    public function testLeaf()
    {
        self::assertCount(12, User::leaf()->get());
    }

    public function testRoot()
    {
        self::assertCount(2, User::root()->get());
    }
}
