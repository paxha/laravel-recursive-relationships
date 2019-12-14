<p align="center">
<a href="https://travis-ci.org/paxha/laravel-recursive-relationships"><img src="https://img.shields.io/travis/paxha/laravel-recursive-relationships/master.svg?style=flat-square" alt="Build Status"></a>
<a href="https://github.styleci.io/repos/227086797"><img src="https://github.styleci.io/repos/227086797/shield?branch=master" alt="StyleCI"></a>
<a href="https://packagist.org/packages/paxha/laravel-recursive-relationships"><img src="https://poser.pugx.org/paxha/laravel-recursive-relationships/d/total.svg?format=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/paxha/laravel-recursive-relationships"><img src="https://poser.pugx.org/paxha/laravel-recursive-relationships/v/stable.svg?format=flat-square" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/paxha/laravel-recursive-relationships"><img src="https://poser.pugx.org/paxha/laravel-recursive-relationships/license.svg?format=flat-square" alt="License"></a>
</p>

## Introduction
This Laravel Eloquent extension provides recursive relationships using common table.

## Installation

    composer require paxha/laravel-recursive-relationships

## Usage

-   [Getting Started](#getting-started)
-   [Relationships](#relationships)
-   [Scopes](#scopes)
-   [Functions](#functions)

### Getting Started

Consider the following table schema for hierarchical data:

```php
Schema::create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->unsignedInteger('parent_id')->nullable();
});
```

Use the `HasRecursiveRelationships` trait in your model to work with recursive relationships:

```php
class User extends Model
{
    use \RecursiveRelationships\Traits\HasRecursiveRelationships;
}
```

By default, the trait expects a parent key named `parent_id`. You can customize it by overriding `getParentKeyName()`:

```php
class User extends Model
{
    use \RecursiveRelationships\Traits\HasRecursiveRelationships;

    public function getParentKeyName()
    {
        return 'user_id'; // or anything
    }
}
```

### Relationships

The trait provides various relationships:

-   `children()`: The model's direct children.
-   `nestedChildren()`: The model's nested children.
-   `parent()`: The model's direct parent.
-   `nestedParents()`: The model's nested parents by object.

```php
$users = User::with('children')->get();

$users = User::with('nestedChildren')->get();

$users = User::with('parent')->get();

$users = User::with('nestedParents')->get();
```

### Scopes

The trait provides query scopes to filter models by their position in the tree:

-   `hasChildren()`: Models with children.
-   `hasParent()`: Models with a parent.
-   `leaf()`: Models without children.
-   `root()`: Models without a parent.

```php
$noLeaves = User::hasChildren()->get();

$noRoots = User::hasParent()->get();

$leaves = User::leaf()->get();

$roots = User::root()->get();
```

### Functions

The trait provides helper functions:

-   `descendents()`: The model's all Children in single array.
-   `ancestors()`: The model's all parents in single array.
-   `siblings()`: The parent's other children.

```php
$descendents = User::find($id)->descendents();

$ancestors = User::find($id)->ancestors();

$siblings = User::find($id)->siblings();
```

## License

This is open-sourced laravel library licensed under the [MIT license](https://opensource.org/licenses/MIT).
