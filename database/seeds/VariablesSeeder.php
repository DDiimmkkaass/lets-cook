<?php

use App\Models\Variable;
use Illuminate\Database\Seeder;

class VariablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $_fakers = make_locales_fakers();

        $variables = [
            'stop_ordering_date' => [
                'type'         => 'weekday',
                'name'         => 'День закрытие приема заказов',
                'description'  => 'День недели, когда прекращается прием заявок на текущую неделю',
                'multilingual' => false,
                'status'       => true,
            ],
            'stop_ordering_time' => [
                'type'         => 'time',
                'name'         => 'Время закрытие приема заказов',
                'description'  => 'Время, когда прекращается прием заявок на текущую неделю',
                'multilingual' => false,
                'status'       => true,
            ],

            'finalising_reports_date' => [
                'type'         => 'weekday',
                'name'         => 'День финализации отчетов',
                'description'  => 'День недели, когда происходит финализация отчетов',
                'multilingual' => false,
                'status'       => true,
            ],
            'finalising_reports_time' => [
                'type'         => 'time',
                'name'         => 'Время финализации отчетов',
                'description'  => 'Время, когда происходит финализация отчетов',
                'multilingual' => false,
                'status'       => true,
            ],
        ];

        foreach ($variables as $key => $variable) {
            if (!Variable::whereKey($key)->first()) {
                $variable['key'] = $key;

                if ($variable['multilingual'] == true) {
                    unset($variable['value']);

                    foreach (config('app.locales') as $locale) {
                        $variable[$locale] = [
                            'text' => config('app.env') == 'production' ?
                                '' :
                                $_fakers[$locale]->{isset($variable['faker']) ? $variable['faker'] : 'realText'}(),
                        ];
                    }

                    unset($variable['faker']);
                }

                $model = new Variable($variable);
                $model->type = Variable::getTypeKeyByName($variable['type']);

                $model->save();
            }
        }
    }
}
