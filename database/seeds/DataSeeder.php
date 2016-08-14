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
            $this->call(_CategoriesSeeder::class);
            $this->call(_UnitsSeeder::class);
            $this->call(_ParametersSeeder::class);
            $this->call(_NutritionalValuesSeeder::class);
            $this->call(_IngredientsSeeder::class);
            $this->call(_BasketsSeeder::class);
            $this->call(_RecipesSeeder::class);
            $this->call(_WeeklyMenusSeeder::class);
            $this->call(_CitiesSeeder::class);
            $this->call(_UsersSeeder::class);
            $this->call(_OrdersSeeder::class);
            $this->call(_TagCategoriesSeeder::class);
            $this->call(_TagsSeeder::class);
            
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
