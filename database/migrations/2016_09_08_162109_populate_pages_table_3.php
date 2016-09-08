<?php

use App\Models\Page;
use App\Services\PageService;
use Illuminate\Database\Migrations\Migration;

class PopulatePagesTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pageService = new PageService();
        
        $input = [
            'slug'     => 'podpiskа',
            'position' => 0,
            'status'   => 1,
            'template' => 'default',
        ];
        
        foreach (config('app.locales') as $locale) {
            $input[$locale] = [
                'name'             => 'Подписка',
                'short_content'    => 'Подписка',
                'content'          => 'Подписка',
                'meta_keywords'    => 'Подписка',
                'meta_title'       => 'Подписка',
                'meta_description' => 'Подписка',
            ];
        }
        
        if (!Page::whereSlug($input['slug'])->first()) {
            $model = new Page($input);
            $model->save();
            $pageService->setExternalUrl($model);
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
