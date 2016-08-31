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
        
        if (!Menu::whereLayoutPosition($widget)->first()) {
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
                    'link'     => '/pages/pervyj-raz',
                    'name'     => 'Первый раз',
                    'position' => 0,
                ],
                [
                    'link'     => '/baskets',
                    'name'     => 'Корзины',
                    'position' => 1,
                ],
                [
                    'link'     => '/articles',
                    'name'     => 'Статьи',
                    'position' => 2,
                ],
                [
                    'link'     => '/blog',
                    'name'     => 'Блог',
                    'position' => 3,
                ],
                [
                    'link'     => '/questions',
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
        }
        
        // footer_additional
        $widget = 'footer_additional';
        
        if (!Menu::whereLayoutPosition($widget)->first()) {
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
                    'link'     => '/pages/dostavka-i-oplata',
                    'name'     => 'Доставка и оплата',
                    'position' => 0,
                ],
                [
                    'link'     => '/pages/polzovatelskoe-soglashenie',
                    'name'     => 'Пользовательское соглашение',
                    'position' => 1,
                ],
                [
                    'link'     => '/pages/politika-obrabotki-personalnyh-dannyh',
                    'name'     => 'Политика обработки персональных данных',
                    'position' => 2,
                ],
                [
                    'link'     => '/contacts',
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
