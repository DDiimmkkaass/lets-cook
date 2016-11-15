<?php

use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable11 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $variable = [
            'key'    => 'comment_user_default_image',
            'type'   => 'image',
            'name'   => 'Дефолтная картинка пользователя для комментариев',
            'status' => true,
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
