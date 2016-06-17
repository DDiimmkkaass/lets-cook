<?php

use Illuminate\Database\Migrations\Migration;

class PopulatePagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $id = DB::table('pages')->insertGetId(
            [
                'parent_id'  => null,
                'slug'       => 'home',
                'position'   => 0,
                'status'     => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        DB::table('page_translations')->insert([['page_id' => $id, 'name' => 'Главная', 'locale' => 'ru']]);
        DB::table('page_translations')->insert([['page_id' => $id, 'name' => 'Головна', 'locale' => 'uk']]);
        DB::table('page_translations')->insert([['page_id' => $id, 'name' => 'Home', 'locale' => 'en']]);
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
