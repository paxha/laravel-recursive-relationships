<p align="center">
<a href="https://travis-ci.org/paxha/laravel-recursive-relationship"><img src="https://img.shields.io/travis/paxha/laravel-recursive-relationship/master.svg?style=flat-square" alt="Build Status"></a>
<a href="https://github.styleci.io/repos/227086797"><img src="https://github.styleci.io/repos/227086797/shield?branch=master" alt="StyleCI"></a>
<a href="https://packagist.org/packages/paxha/laravel-recursive-relationship"><img src="https://poser.pugx.org/paxha/laravel-recursive-relationship/d/total.svg?format=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/paxha/laravel-recursive-relationship"><img src="https://poser.pugx.org/paxha/laravel-recursive-relationship/v/stable.svg?format=flat-square" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/paxha/laravel-recursive-relationship"><img src="https://poser.pugx.org/paxha/laravel-recursive-relationship/license.svg?format=flat-square" alt="License"></a>
</p>

## Introduction
This Laravel Eloquent extension provides recursive relationships using common table.

## Installation

    composer require paxha/laravel-recursive-relationship

## Usage

-   [Getting Started](#getting-started)
-   [Relationships](#relationships)
-   [Filters](#filters)

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

-   `ancestors()`: The model's recursive parents.
-   `children()`: The model's direct children.
-   `descendants()`: The model's recursive children.
-   `parent()`: The model's direct parent.
-   `siblings()`: The parent's other children.

```php
$ancestors = User::find($id)->ancestors;

$users = User::with('descendants')->get();

$users = User::whereHas('siblings', function ($query) {
    $query->where('name', '=', $name);
})->get();

$total = User::find($id)->descendants()->count();

User::find($id)->descendants()->update(['active' => false]);

/*Root parents will not get siblings*/
User::find($id)->siblings()->delete();
```

### Filters

The trait provides query scopes to filter models by their position in the tree:

-   `hasChildren()`: Models with children.
-   `hasParent()`: Models with a parent.
-   `isLeaf()`: Models without children.
-   `isRoot()`: Models without a parent.

```php
$noLeaves = User::hasChildren()->get();

$noRoots = User::hasParent()->get();

$leaves = User::leaf()->get();

$roots = User::root()->get();
```

## License

This is open-sourced laravel library licensed under the [MIT license](https://opensource.org/licenses/MIT).
