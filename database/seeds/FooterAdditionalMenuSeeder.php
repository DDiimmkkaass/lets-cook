<?php

use App\Models\Menu;
use App\Models\MenuItem;

class FooterAdditionalMenuSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
