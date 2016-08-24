<?php
use App\Models\Basket;
use App\Models\Recipe;

/**
 * Class _AdditionalBasketsRecipesSeeder
 */
class _AdditionalBasketsRecipesSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Basket::with('recipes')->additional()->get() as $basket) {
            if (count($basket->recipes)) {
                for ($i = 0; $i < rand(1, 2); $i++) {
                    $basket->recipes()->create([
                        'recipe_id' => $basket->recipes->random()->id,
                    ]);
                }
            }
        }
    }
}
