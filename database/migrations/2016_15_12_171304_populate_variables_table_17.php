<?php

use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable17 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $variable = [
            'key'         => 'order_email',
            'type'        => 'text',
            'name'        => 'E-mail для сообщений о заказах',
            'description' => 'E-mail для отправки сообщений о новых заказах на сайте',
            'status'      => true,
            'value'       => 'info@davaigotovit.ru',
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
