<?php

use App\Models\Article;
use App\Models\Tag;
use App\Models\Tagged;

class _ArticlesSeeder extends DataSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::whereNotNull('id')->delete();
        DB::statement('ALTER TABLE `'.((new Article())->getTable()).'` AUTO_INCREMENT=1');
        
        foreach (range(1, 25) as $index) {
            $input = [
                'slug'     => $this->faker->slug(),
                'position' => $index,
                'status'   => 1,
                'image'    => $this->faker->imageUrl(250, 250, 'city'),
            ];
            
            foreach (Config::get('app.locales') as $locale) {
                $input[$locale] = [
                    'name'             => $this->getLocalizedFaker()->realText(rand(20, 50)),
                    'short_content'    => '<p>'.$this->getLocalizedFaker()->realText(250).'</p>',
                    'content'          => '<p>'.$this->getLocalizedFaker()->realText(500).'</p>',
                    'meta_title'       => $this->getLocalizedFaker()->sentence(rand(5, 10)),
                    'meta_keywords'    => $this->getLocalizedFaker()->sentence(rand(5, 10)),
                    'meta_description' => $this->getLocalizedFaker()->text(150),
                ];
            }
            
            $model = new Article($input);
            $model->save();
            
            $tags = Tag::all()->random(rand(2, 5))->toArray();
            foreach ((array) $tags as $tag) {
                $tagged = new Tagged(['tag_id' => $tag['id']]);
                
                $model->tags()->save($tagged);
            }
        }
    }
}
