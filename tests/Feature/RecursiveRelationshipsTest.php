<?php

namespace RecursiveRelationships\Tests\Feature;

use RecursiveRelationships\Tests\Models\User;
use RecursiveRelationships\Tests\TestCase;

class RecursiveRelationshipsTest extends TestCase
{
    public function testChildren()
    {
        $user = User::all()->first();

        $this->assertGreaterThan(1, $user->children()->count());
    }

    public function testDescendants()
    {
        $user = User::all()->first();
        $this->assertEquals(0, $user->parent()->count());
        if ($user->descendants()->count()) {
            $this->testDescendantsRecursive($user->descendants);
        }
    }

    private function testDescendantsRecursive($descendants)
    {
        foreach ($descendants as $descendant) {
            $this->assertEquals(1, $descendant->parent()->count());
            if ($descendant->descendants()->count()) {
                $this->testDescendantsRecursive($descendant->descendants);
            }
        }
    }

    public function testParent()
    {
        $user = User::all()->first();

        $child = $user->children()->first();

        $this->assertEquals(1, $child->parent()->count());
    }

    public function testAncestor()
    {
        $user = User::with('ancestor')->where('user_id', '!=', null)->get()->last();

        if ($user->ancestor) {
            $this->testAncestorRecursive($user->ancestor);
        }
    }

    private function testAncestorRecursive($ancestor)
    {
        if ($ancestor->parent) {
            $this->assertEquals(1, $ancestor->parent()->count());
        } else {
            $this->assertEquals(0, $ancestor->parent()->count());
        }

        if ($ancestor->ancestor) {
            $this->testAncestorRecursive($ancestor->ancestor);
        }
    }

    public function testSiblings()
    {
        $user = User::all()->last();

        foreach ($user->siblings as $sibling) {
            $this->assertEquals($user->user_id, $sibling->user_id);
        }
    }

    public function testHasChildren()
    {
        $users = User::hasChildren()->get();

        foreach ($users as $user) {
            $this->assertGreaterThan(1, $user->children()->count());
        }
    }

    public function testHasParent()
    {
        $users = User::hasParent()->get();

        foreach ($users as $user) {
            $this->assertEquals(1, $user->parent()->count());
        }
    }

    public function testLeaf()
    {
        $users = User::leaf()->get();

        foreach ($users as $user) {
            $this->assertEquals(1, $user->parent()->count());
            $this->assertEquals(0, $user->children()->count());
        }
    }

    public function testRoot()
    {
        $users = User::root()->get();

        foreach ($users as $user) {
            $this->assertEquals(0, $user->parent()->count());
            $this->assertGreaterThan(1, $user->children()->count());
        }
    }
}
