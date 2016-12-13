<?php

use App\Models\Basket;
use App\Models\Variable;
use Illuminate\Database\Migrations\Migration;

class PopulateVariablesTable16 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $basket = Basket::whereSlug('novogodnyaya')->first();
        
        //new year basket
        $variable = [
            'key'         => 'new_year_basket_slug',
            'type'        => 'text',
            'value'       => $basket ? $basket->slug : '',
            'name'        => 'Slug новогодней корзины',
            'description' => 'Slug корзины, которая будет использоваться в качестве новогодней',
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
