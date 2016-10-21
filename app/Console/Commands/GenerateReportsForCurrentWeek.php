<?php

namespace App\Console\Commands;

use App\Services\PurchaseService;
use Exception;

/**
 * Class GenerateReportsForCurrentWeek
 * @package App\Console\Commands
 */
class GenerateReportsForCurrentWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:generate-reports-for-current-week';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate purchase & packaging lists for current week';
    
    /**
     * @var \App\Services\OrderService
     */
    private $purchaseService;
    
    /**
     * Create a new command instance.
     *
     * @param \App\Services\PurchaseService $purchaseService
     */
    public function __construct(PurchaseService $purchaseService)
    {
        parent::__construct();
        
        $this->purchaseService = $purchaseService;
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->log('Start '.$this->description);
        
        try {
            $this->purchaseService->generate();
        } catch (Exception $e) {
            $message = $e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile();
            
            $this->log($message, 'error');
            
            admin_notify($this->description.' error: '.$message);
        }
        
        $this->log('End '.$this->description);
    }
}
