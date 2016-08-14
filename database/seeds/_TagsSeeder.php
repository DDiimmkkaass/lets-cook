<?php
use App\Models\Tag;
use App\Models\TagCategory;

/**
 * Class _TagsSeeder
 */
class _TagsSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Tag())->getTable()).'` AUTO_INCREMENT=1');
        
        foreach (range(1, 10) as $index) {
            $input = [
                'category_id' => TagCategory::all()->random()->id,
            ];
            
            foreach (Config::get('app.locales') as $locale) {
                $input[$locale] = [
                    'name' => $this->_fakers[config('app.locale')]->word,
                ];
            }
            
            Tag::create($input);
        }
    }
}
