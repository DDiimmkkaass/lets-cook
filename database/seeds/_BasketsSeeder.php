<?php
use App\Models\Basket;
use App\Models\Recipe;

/**
 * Class _BasketsSeeder
 */
class _BasketsSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Basket::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Basket())->getTable()).'` AUTO_INCREMENT=1');

        for ($i = 0; $i < 5; $i++) {
            $input = [
                'name'     => $this->getLocalizedFaker()->word,
                'type'     => Basket::getTypeIdByName('basic'),
                'position' => $i,
                'price'    => rand(3000, 10000),
            ];

            $model = new Basket($input);
            $model->save();
        }

        foreach (range(1, 10) as $index) {
            $input = [
                'name'     => $this->getLocalizedFaker()->word,
                'type'     => Basket::getTypeIdByName('additional'),
                'position' => $index,
                'price'    => rand(3000, 10000),
            ];

            $model = new Basket($input);
            $model->save();
        }
    }
}
