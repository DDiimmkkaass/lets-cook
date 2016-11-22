<?php

use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable13 extends Migration
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
                'key'         => 'invite_friend_discount',
                'type'        => 'text',
                'name'        => 'Скидка для друга',
                'description' => 'Скидка для друга по программе "Пригласи друга"',
                'status'      => true,
                'value'       => 0,
            ],
            
            [
                'key'         => 'invite_friend_compensation',
                'type'        => 'text',
                'name'        => 'Компенсация за успешный заказ друга',
                'description' => 'Компенсация пользователю при успешном заказе друга по его купону',
                'status'      => true,
                'value'       => 0,
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
