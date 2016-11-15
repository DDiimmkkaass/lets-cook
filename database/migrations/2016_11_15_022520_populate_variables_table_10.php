<?php

use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable10 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $variable = [
            'key'    => 'home_main_image',
            'type'   => 'image',
            'name'   => 'Картинка на главную',
            'status' => true,
            'value'  => '',
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
