<?php

namespace App\Console\Commands;

use App\Models\Order;

/**
 * Class ProcessPaidOrdersForCurrentWeek
 * @package App\Console\Commands
 */
class ProcessPaidOrdersForCurrentWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-paid-orders-for-current-week';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set "processed" status for all "paid" orders with delivery for the current week';
    
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->log('Start '.$this->description);
        
        Order::ofStatus('paid')->forCurrentWeek()->update(['status' => Order::getStatusIdByName('processed')]);
        
        $this->log('End '.$this->description);
    }
}
