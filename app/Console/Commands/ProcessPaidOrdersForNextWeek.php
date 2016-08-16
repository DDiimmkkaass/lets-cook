<?php

namespace App\Console\Commands;

use App\Models\Order;

/**
 * Class ProcessPaidOrdersForNextWeek
 * @package App\Console\Commands
 */
class ProcessPaidOrdersForNextWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-paid-orders-for-next-week';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set "processed" status for all "paid" orders with delivery for the next week';
    
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
        
        Order::ofStatus('paid')->forNextWeek()->update(['status' => Order::getStatusIdByName('processed')]);
        
        $this->log('End '.$this->description);
    }
}
