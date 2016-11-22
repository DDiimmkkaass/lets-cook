<?php

use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable12 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $variable = [
            'key'         => 'loyalty_program_status',
            'type'        => 'boolean',
            'name'        => 'Программа лояльности',
            'description' => 'Включить/выключить программу лояльности',
            'status'      => true,
            'value'       => true,
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
