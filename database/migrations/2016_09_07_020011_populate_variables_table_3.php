<?php

use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //map marker latitude
        $variable = [
            'key'         => 'map_marker_latitude',
            'type'        => 'text',
            'value'       => '55.7494733',
            'name'        => 'Широта',
            'description' => 'Широта для маркера на карте на странице контактов',
            'status'      => true,
        ];
        
        if (!Variable::whereKey($variable['key'])->first()) {
            $model = new Variable($variable);
            $model->type = Variable::getTypeKeyByName($variable['type']);
            
            $model->save();
        }
    
        //map marker longitude
        $variable = [
            'key'         => 'map_marker_longitude',
            'type'        => 'text',
            'value'       => '37.61232',
            'name'        => 'Долгота',
            'description' => 'Долгота для маркера на карте на странице контактов',
            'status'      => true,
        ];
    
        if (!Variable::whereKey($variable['key'])->first()) {
            $model = new Variable($variable);
            $model->type = Variable::getTypeKeyByName($variable['type']);
        
            $model->save();
        }
    
        //address
        $variable = [
            'key'         => 'address',
            'type'        => 'text',
            'value'       => 'г. Москва',
            'name'        => 'Адрес',
            'status'      => true,
        ];
    
        if (!Variable::whereKey($variable['key'])->first()) {
            $model = new Variable($variable);
            $model->type = Variable::getTypeKeyByName($variable['type']);
        
            $model->save();
        }
    
        //work time
        $variable = [
            'key'         => 'work_time',
            'type'        => 'text',
            'value'       => '09:00-22:00',
            'name'        => 'Время работы',
            'status'      => true,
        ];
    
        if (!Variable::whereKey($variable['key'])->first()) {
            $model = new Variable($variable);
            $model->type = Variable::getTypeKeyByName($variable['type']);
        
            $model->save();
        }
    
        //work days
        $variable = [
            'key'         => 'work_days',
            'type'        => 'text',
            'value'       => 'Без выходных',
            'name'        => 'Рабочие дни',
            'status'      => true,
        ];
    
        if (!Variable::whereKey($variable['key'])->first()) {
            $model = new Variable($variable);
            $model->type = Variable::getTypeKeyByName($variable['type']);
        
            $model->save();
        }
    
        //phone 2
        $variable = [
            'key'         => 'phone_2',
            'type'        => 'text',
            'value'       => '8 (499) 390–98–87',
            'name'        => 'Дополнительный телефон',
            'status'      => true,
        ];
    
        if (!Variable::whereKey($variable['key'])->first()) {
            $model = new Variable($variable);
            $model->type = Variable::getTypeKeyByName($variable['type']);
        
            $model->save();
        }
    
        //phone 3
        $variable = [
            'key'         => 'phone_3',
            'type'        => 'text',
            'value'       => '8 (499) 390–98–87',
            'name'        => 'Дополнительный телефон',
            'status'      => true,
        ];
    
        if (!Variable::whereKey($variable['key'])->first()) {
            $model = new Variable($variable);
            $model->type = Variable::getTypeKeyByName($variable['type']);
        
            $model->save();
        }
    
        //contact email
        $variable = [
            'key'         => 'contact_email',
            'type'        => 'text',
            'value'       => 'info@davaigotovit.ru',
            'name'        => 'Контактный email',
            'status'      => true,
        ];
    
        if (!Variable::whereKey($variable['key'])->first()) {
            $model = new Variable($variable);
            $model->type = Variable::getTypeKeyByName($variable['type']);
        
            $model->save();
        }
    
        //skype name
        $variable = [
            'key'         => 'skype_name',
            'type'        => 'text',
            'value'       => 'davaigotovit.ru',
            'name'        => 'Скайп',
            'status'      => true,
        ];
    
        if (!Variable::whereKey($variable['key'])->first()) {
            $model = new Variable($variable);
            $model->type = Variable::getTypeKeyByName($variable['type']);
        
            $model->save();
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
