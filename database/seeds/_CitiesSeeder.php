<?php
use App\Models\City;

/**
 * Class _CitiesSeeder
 */
class _CitiesSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new City())->getTable()).'` AUTO_INCREMENT=1');

        foreach (range(1, 5) as $index) {
            $input = [
                'name'     => $this->faker->city,
                'position' => $index,
            ];

            City::create($input);
        }
    }
}
