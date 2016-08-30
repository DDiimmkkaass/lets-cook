<?php

use App\Services\PageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * @var \App\Services\PageService
     */
    public $pageService;
    
    /**
     * DatabaseSeeder constructor.
     *
     * @param \App\Services\PageService $pageService
     */
    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
    
        $this->call(VariablesSeeder::class);
        $this->call(PagesSeeder::class);
        $this->call(MainMenuSeeder::class);
        $this->call(FooterAdditionalMenuSeeder::class);
        
        Model::reguard();
    }
}
