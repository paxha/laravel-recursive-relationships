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
        /*2 Root Users*/
        factory(User::class, 2)->create()->each(function ($user) {
            $faker = Faker::create();

            /*
             * Each Root Has 2 Children
             * Total 4
             * */
            for ($index = 1; $index <= 2; $index++) {
                $u = $user->children()->create([
                    'name' => $faker->name,
                ]);

                /*
                 * Each child has contains further three children
                 * Total 12
                 * Grand Total 18
                 * */
                for ($index1 = 1; $index1 <= 3; $index1++) {
                    $u->children()->create([
                        'name' => $faker->name,
                    ]);
                }
            }
        });
    }
}
