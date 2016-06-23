<?php
use App\Models\Basket;

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

        foreach (range(1, 5) as $index) {
            $input = [
                'name'     => $this->getLocalizedFaker()->word,
                'position' => $index,
            ];

            Basket::create($input);
        }
    }
}
