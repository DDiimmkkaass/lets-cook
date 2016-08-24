<?php

use App\Models\Parameter;

/**
 * Class _ParametersSeeder
 */
class _ParametersSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parameter::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Parameter())->getTable()).'` AUTO_INCREMENT=1');
        
        foreach (range(1, 5) as $index) {
            $input = [
                'name'     => title_case($this->getLocalizedFaker()->word),
                'package'  => rand(1, 2),
                'position' => $index,
            ];
            
            Parameter::create($input);
        }
    }
}
