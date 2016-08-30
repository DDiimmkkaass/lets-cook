<?php

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MainMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
        }
    }
}
