<?php
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\IngredientNutritionalValue;
use App\Models\NutritionalValue;
use App\Models\Parameter;
use App\Models\Supplier;
use App\Models\Unit;

/**
 * Class _IngredientsSeeder
 */
class _IngredientsSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ingredient::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Ingredient())->getTable()).'` AUTO_INCREMENT=1');
        
        foreach (range(1, 20) as $index) {
            $input = [
                'name'        => $this->getLocalizedFaker()->word,
                'title'       => $this->getLocalizedFaker()->word,
                'image'       => $this->getLocalizedFaker()->imageUrl(250, 250, 'food'),
                'price'       => rand(1, 5000),
                'supplier_id' => Supplier::all()->random(1)->id,
                'category_id' => Category::all()->random(1)->id,
                'unit_id'     => Unit::all()->random(1)->id,
            ];
            
            $ingredient = Ingredient::create($input);
            
            $parameter_id = Parameter::all()->random()->id;
            $ingredient->parameters()->sync([$parameter_id]);
            
            foreach (NutritionalValue::all() as $item) {
                IngredientNutritionalValue::create(
                    [
                        'ingredient_id'        => $ingredient->id,
                        'nutritional_value_id' => $item->id,
                        'value'                => rand(10, 500),
                    ]
                );
            }
        }
    }
}
