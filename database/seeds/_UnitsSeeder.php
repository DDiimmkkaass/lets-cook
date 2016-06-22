<?php
use App\Models\Unit;

/**
 * Class _UnitsSeeder
 */
class _UnitsSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Unit())->getTable()).'` AUTO_INCREMENT=1');

        foreach (range(1, 5) as $index) {
            $input = [
                'name'     => $this->getLocalizedFaker()->word,
                'position' => $index,
            ];

            Unit::create($input);
        }
    }
}
