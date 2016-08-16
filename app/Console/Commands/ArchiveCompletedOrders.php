<?php

namespace App\Console\Commands;

use App\Models\Order;

/**
 * Class ArchiveCompletedOrders
 * @package App\Console\Commands
 */
class ArchiveCompletedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:archive-completed-orders';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive completed on current week orders';
    
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
        
        Order::ofStatus('processed')->update(['status' => Order::getStatusIdByName('archived')]);
        
        $this->log('End '.$this->description);
    }
}
