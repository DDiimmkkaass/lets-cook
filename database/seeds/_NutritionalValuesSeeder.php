<?php

use App\Models\NutritionalValue;

/**
 * Class _NutritionalValuesSeeder
 */
class _NutritionalValuesSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NutritionalValue::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new NutritionalValue())->getTable()).'` AUTO_INCREMENT=1');

        foreach (range(1, 5) as $index) {
            $input = [
                'name'     => title_case($this->getLocalizedFaker()->word),
                'position' => $index,
            ];

            NutritionalValue::create($input);
        }
    }
}
