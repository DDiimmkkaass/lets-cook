<?php

use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable9 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $variable = [
            'key'         => 'user_activation_bcc_email',
            'type'        => 'text',
            'name'        => 'E-mail для копии письма активации',
            'description' => 'E-mail для отправки копии письма активации при регистрации пользователя',
            'status'      => true,
            'value'       => 'customers@davaigotovit.ru',
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
