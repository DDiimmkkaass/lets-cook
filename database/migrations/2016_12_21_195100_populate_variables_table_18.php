<?php

use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable18 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $variables = [
            [
                'key'         => 'invite_friend_discount_type',
                'type'        => 'discount_type',
                'name'        => 'Тип скидки для друга',
                'description' => 'Тип скидка для друга по программе "Пригласи друга"',
                'status'      => true,
                'value'       => 'absolute',
            ],
        
            [
                'key'         => 'invite_friend_compensation_type',
                'type'        => 'discount_type',
                'name'        => 'Тип компенсации за успешный заказ друга',
                'description' => 'Тип компенсации пользователю при успешном заказе друга по его купону',
                'status'      => true,
                'value'       => 'percentage',
            ],
        ];
    
        foreach ($variables as $variable) {
            if (!Variable::whereKey($variable['key'])->first()) {
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
