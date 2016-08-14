<?php
use App\Models\TagCategory;

/**
 * Class _TagCategoriesSeeder
 */
class _TagCategoriesSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TagCategory::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new TagCategory())->getTable()).'` AUTO_INCREMENT=1');

        foreach (range(1, 10) as $index) {
            $input = [
                'name'     => $this->getLocalizedFaker()->word,
                'position' => $index,
            ];

            TagCategory::create($input);
        }
    }
}
