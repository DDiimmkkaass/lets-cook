<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

class PopulatePagesTableSetExternalUrl extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        $pageService = App::make('\App\Services\PageService');

        foreach (Page::whereNull('external_url')->get() as $page) {
            $pageService->setExternalUrl($page);
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
