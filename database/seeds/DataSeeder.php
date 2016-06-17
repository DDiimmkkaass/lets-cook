<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Class DataSeeder
 */
class DataSeeder extends Seeder
{
    /**
     * @var \Faker\Generator
     */
    public $faker;

    /**
     * @var array of \Faker\Generator
     */
    public $_fakers;

    /**
     * DataSeeder constructor.
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create();

        $this->_fakers = make_locales_fakers();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') !== 'production') {
            Model::unguard();

            $this->call(_SuppliersSeeder::class);

            Model::reguard();
        }
    }

    /**
     * @return \Faker\Generator
     */
    public function getLocalizedFaker()
    {
        return $this->_fakers[config('app.locale')];
    }
}
