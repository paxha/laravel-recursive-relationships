<?php

namespace RecursiveRelationships\Tests;

use Faker\Factory as Faker;
use RecursiveRelationships\Tests\Models\User;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->withFactories(__DIR__.'/database/factories');

        $this->seeds();
    }

    protected function seeds()
    {
        factory(User::class, 2)->create()->each(function ($user) {
            $faker = Faker::create();
            for ($index = 1; $index <= 2; $index++) {
                $u = $user->children()->create([
                    'name' => $faker->name,
                ]);
                for ($index1 = 1; $index1 <= 2; $index1++) {
                    $u1 = $u->children()->create([
                        'name' => $faker->name,
                    ]);
                    for ($index2 = 1; $index2 <= 2; $index2++) {
                        $u2 = $u1->children()->create([
                            'name' => $faker->name,
                        ]);
                        for ($index3 = 1; $index3 <= 3; $index3++) {
                            $u2->children()->create([
                                'name' => $faker->name,
                            ]);
                        }
                    }
                }
            }
        });
    }
}
