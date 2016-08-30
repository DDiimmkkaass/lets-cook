<?php

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;

class PopulateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //main
        $widget = 'main';
        
        $input = [
            'layout_position' => $widget,
            'status'          => true,
            'position'        => 0,
            'template'        => $widget,
        ];
        
        foreach (config('app.locales') as $locale) {
            $input[$locale] = [
                'name' => 'Главное меню',
            ];
        }
        
        $menu = Menu::create($input);
        
        $items = [
            [
                'link'     => url('pages/pervyj-raz'),
                'name'     => 'Первый раз',
                'position' => 0,
            ],
            [
                'link'     => route('baskets.index'),
                'name'     => 'Корзины',
                'position' => 1,
            ],
            [
                'link'     => route('articles.index'),
                'name'     => 'Статьи',
                'position' => 2,
            ],
            [
                'link'     => route('blog.index'),
                'name'     => 'Блог',
                'position' => 3,
            ],
            [
                'link'     => route('questions.index'),
                'name'     => 'Вопросы и ответы',
                'position' => 4,
            ],
        ];
        
        foreach ($items as $item) {
            foreach (config('app.locales') as $locale) {
                $item[$locale] = [
                    'name' => $item['name'],
                ];
            }
            
            $item = new MenuItem($item);
            
            $menu->items()->save($item);
        }
        
        // footer_additional
        $widget = 'footer_additional';
        
        $input = [
            'layout_position' => $widget,
            'status'          => true,
            'position'        => 0,
            'template'        => $widget,
        ];
        
        foreach (config('app.locales') as $locale) {
            $input[$locale] = [
                'name' => 'Меню в футере',
            ];
        }
        
        $menu = Menu::create($input);
        
        $items = [
            [
                'link'     => url('pages/dostavka-i-oplata'),
                'name'     => 'Доставка и оплата',
                'position' => 0,
            ],
            [
                'link'     => url('pages/polzovatelskoe-soglashenie'),
                'name'     => 'Пользовательское соглашение',
                'position' => 1,
            ],
            [
                'link'     => url('pages/politika-obrabotki-personalnyh-dannyh'),
                'name'     => 'Политика обработки персональных данных',
                'position' => 2,
            ],
            [
                'link'     => route('contacts'),
                'name'     => 'Контакты',
                'position' => 3,
            ],
        ];
        
        foreach ($items as $item) {
            foreach (config('app.locales') as $locale) {
                $item[$locale] = [
                    'name' => $item['name'],
                ];
            }
            
            $item = new MenuItem($item);
            
            $menu->items()->save($item);
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
