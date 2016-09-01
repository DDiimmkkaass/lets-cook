<?php

use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $variables = [
            'stop_ordering_date' => [
                'type'        => 'weekday',
                'name'        => 'День закрытия приема заказов',
                'description' => 'День недели, когда прекращается прием заявок на текущую неделю',
                'status'      => true,
            ],
            'stop_ordering_time' => [
                'type'        => 'time',
                'name'        => 'Время закрытия приема заказов',
                'description' => 'Время, когда прекращается прием заявок на текущую неделю',
                'status'      => true,
            ],
            
            'finalising_reports_date' => [
                'type'        => 'weekday',
                'name'        => 'День финализации отчетов',
                'description' => 'День недели, когда происходит финализация отчетов',
                'status'      => true,
            ],
            'finalising_reports_time' => [
                'type'        => 'time',
                'name'        => 'Время финализации отчетов',
                'description' => 'Время, когда происходит финализация отчетов',
                'status'      => true,
            ],
            
            'full_company_name' => [
                'type'   => 'text',
                'name'   => 'Полное название компании',
                'status' => true,
                'value'  => 'ООО «Доставка Здорового Питания»',
            ],
            
            'moscow_phone' => [
                'type'   => 'text',
                'name'   => 'Телефон в Москве',
                'status' => true,
                'value'  => '8 (499) 390–98–98',
            ],
        ];
        
        foreach ($variables as $key => $variable) {
            if (!Variable::whereKey($key)->first()) {
                $variable['key'] = $key;
                
                $model = new Variable($variable);
                $model->type = Variable::getTypeKeyByName($variable['type']);
                
                $model->save();
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
