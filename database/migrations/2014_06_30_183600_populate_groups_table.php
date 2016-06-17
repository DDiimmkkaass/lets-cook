<?php

use Illuminate\Database\Migrations\Migration;

class PopulateGroupsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Sentry::getGroupProvider()->create(
            [
                'name'        => 'Administrators',
                'permissions' => ['superuser' => 1],
            ]
        );

        Sentry::getGroupProvider()->create(
            [
                'name'        => 'Clients',
                'permissions' => [],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}