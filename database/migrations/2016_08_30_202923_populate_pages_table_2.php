<?php

use App\Models\Page;
use App\Services\PageService;
use Illuminate\Database\Migrations\Migration;

class PopulatePagesTable2 extends Migration
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
            'slug'     => 'pervyj-raz',
            'position' => 0,
            'status'   => 1,
            'template' => 'default',
        ];
        
        foreach (config('app.locales') as $locale) {
            $input[$locale] = [
                'name'             => 'Первый раз',
                'short_content'    => 'Первый раз',
                'content'          => 'Первый раз',
                'meta_keywords'    => 'Первый раз',
                'meta_title'       => 'Первый раз',
                'meta_description' => 'Первый раз',
            ];
        }
        
        if (!Page::whereSlug($input['slug'])->first()) {
            $model = new Page($input);
            $model->save();
            $pageService->setExternalUrl($model);
        }
        
        $input = [
            'slug'     => 'dostavka-i-oplata',
            'position' => 0,
            'status'   => 1,
            'template' => 'default',
        ];
        
        foreach (config('app.locales') as $locale) {
            $input[$locale] = [
                'name'             => 'Доставка и оплата',
                'short_content'    => 'Доставка и оплата',
                'content'          => 'Доставка и оплата',
                'meta_keywords'    => 'Доставка и оплата',
                'meta_title'       => 'Доставка и оплата',
                'meta_description' => 'Доставка и оплата',
            ];
        }
        
        if (!Page::whereSlug($input['slug'])->first()) {
            $model = new Page($input);
            $model->save();
            $pageService->setExternalUrl($model);
        }
        
        $input = [
            'slug'     => 'polzovatelskoe-soglashenie',
            'position' => 0,
            'status'   => 1,
            'template' => 'default',
        ];
        
        foreach (config('app.locales') as $locale) {
            $input[$locale] = [
                'name'             => 'Пользовательское соглашение',
                'short_content'    => 'Пользовательское соглашение',
                'content'          => 'Пользовательское соглашение',
                'meta_keywords'    => 'Пользовательское соглашение',
                'meta_title'       => 'Пользовательское соглашение',
                'meta_description' => 'Пользовательское соглашение',
            ];
        }
        
        if (!Page::whereSlug($input['slug'])->first()) {
            $model = new Page($input);
            $model->save();
            $pageService->setExternalUrl($model);
        }
        
        $input = [
            'slug'     => 'politika-obrabotki-personalnyh-dannyh',
            'position' => 0,
            'status'   => 1,
            'template' => 'default',
        ];
        
        foreach (config('app.locales') as $locale) {
            $input[$locale] = [
                'name'             => 'Политика обработки персональных данных',
                'short_content'    => 'Политика обработки персональных данных',
                'content'          => 'Политика обработки персональных данных',
                'meta_keywords'    => 'Политика обработки персональных данных',
                'meta_title'       => 'Политика обработки персональных данных',
                'meta_description' => 'Политика обработки персональных данных',
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
