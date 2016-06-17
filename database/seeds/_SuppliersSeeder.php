<?php
use App\Models\Supplier;

/**
 * Class _SuppliersSeeder
 */
class _SuppliersSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Supplier())->getTable()).'` AUTO_INCREMENT=1');

        foreach (range(1, 15) as $index) {
            $input = [
                'name'     => $this->getLocalizedFaker()->company,
                'priority' => $index,
                'comments' => $this->getLocalizedFaker()->realText(rand(50, 200)),
            ];

            Supplier::create($input);
        }
    }
}
