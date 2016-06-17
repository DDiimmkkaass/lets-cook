<?php
use App\Models\Category;

/**
 * Class _CategoriesSeeder
 */
class _CategoriesSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Category())->getTable()).'` AUTO_INCREMENT=1');

        foreach (range(1, 10) as $index) {
            $input = [
                'name'     => $this->getLocalizedFaker()->word,
                'position' => $index,
            ];

            Category::create($input);
        }
    }
}
