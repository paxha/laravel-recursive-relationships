<?php

namespace RecursiveRelationships\Tests\Feature;

use RecursiveRelationships\Tests\Models\User;
use RecursiveRelationships\Tests\TestCase;

class RecursiveRelationshipsTest extends TestCase
{
    public function testChildren()
    {
        $user = User::all()->first();

        self::assertCount(2, $user->children);
    }

    public function testNestedChildren()
    {
        $user = User::all()->first();

        self::assertArrayHasKey('nestedChildren', $user->nestedChildren()->first());
    }

    public function testNestedParents()
    {
        $user = User::all()->last();

        self::assertArrayHasKey('nestedChildren', $user->nestedParents);
    }

    public function testRootHasParent()
    {
        $user = User::all()->first();

        self::assertEmpty($user->parent);
    }

    public function testChildHasParent()
    {
        $user = User::all()->last();

        self::assertIsObject($user->parent);
    }

    public function testHasChildren()
    {
        self::assertCount(6, User::hasChildren()->get());
    }

    public function testHasParent()
    {
        self::assertCount(16, User::hasParent()->get());
    }

    public function testLeaf()
    {
        self::assertCount(12, User::leaf()->get());
    }

    public function testRoot()
    {
        self::assertCount(2, User::root()->get());
    }

    public function testDescendents()
    {
        $user = User::all()->first();

        self::assertCount(8, $user->descendents());
    }

    public function testAncestors()
    {
        $user = User::all()->last();

        self::assertCount(2, $user->ancestors());
    }

    public function testSiblings()
    {
        $user = User::all()->last();

        self::assertCount(2, $user->siblings());
    }
}
