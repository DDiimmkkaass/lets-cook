<?php

use App\Models\Basket;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;

/**
 * Class _RecipesSeeder
 */
class _RecipesSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Recipe::whereNotNull('id')->forceDelete();
        DB::statement('ALTER TABLE `'.((new Recipe())->getTable()).'` AUTO_INCREMENT=1');
        
        foreach (range(1, 40) as $index) {
            $input = [
                'name'           => $this->getLocalizedFaker()->realText(rand(20, 30)),
                'image'          => $this->getLocalizedFaker()->imageUrl(250, 250, 'food'),
                'recipe'         => $this->getLocalizedFaker()->realText(rand(200, 400)),
                'helpful_hints'  => $this->getLocalizedFaker()->realText(rand(100, 300)),
                'portions'       => 2,
                'cooking_time'   => rand(5, 300),
                'home_equipment' => implode('<br>', $this->getLocalizedFaker()->sentences(rand(5, 10))),
            ];
            
            $recipe = new Recipe($input);
            $recipe->save();
            
            $baskets = [];
            $_baskets = Basket::all();
            $count = rand(1, 3);
            for ($i = 0; $i <= $count; $i++) {
                $baskets[] = $_baskets->random(1)->id;
            }
            
            $recipe->baskets()->sync($baskets);
            
            $_ingredients = Ingredient::all();
            foreach (range(1, rand(1, 5)) as $_index) {
                $ingredient = $_ingredients->random(1);
                
                $input = [
                    'recipe_id'     => $recipe->id,
                    'ingredient_id' => $ingredient->id,
                    'count'         => rand(1, 10),
                    'position'      => $_index,
                ];
                
                RecipeIngredient::create($input);
            }
            
            $_ingredients = Ingredient::all();
            foreach (range(1, rand(1, 5)) as $_index) {
                $ingredient = $_ingredients->random(1);
                
                $input = [
                    'recipe_id'     => $recipe->id,
                    'ingredient_id' => $ingredient->id,
                    'count'         => rand(1, 10),
                    'position'      => $_index,
                    'type'          => 1,
                ];
                
                RecipeIngredient::create($input);
            }
            
            foreach (range(1, rand(2, 5)) as $_index) {
                $input = [
                    'recipe_id'   => $recipe->id,
                    'name'        => implode(' ', $this->getLocalizedFaker()->words(rand(1, 3))),
                    'description' => $this->getLocalizedFaker()->realText(rand(50, 200)),
                    'image'       => $this->getLocalizedFaker()->imageUrl(250, 250, 'food'),
                    'position'    => $_index,
                ];
                
                RecipeStep::create($input);
            }
        }
    }
}
