<?php

namespace App\Console;

use App\Console\Commands\ArchiveCompletedOrders;
use App\Console\Commands\ProcessPaidOrdersForNextWeek;
use App\Console\Commands\UpdateSearchIndex;
use App\Console\Commands\ProcessTmplOrdersForNextWeek;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        UpdateSearchIndex::class,
        
        // orders
        ProcessTmplOrdersForNextWeek::class,
        ProcessPaidOrdersForNextWeek::class,
        ArchiveCompletedOrders::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('orders:process-tmp-orders-for-next-week')->cron('0 0 * * 2');
    
        $schedule->command('orders:process-paid-orders-for-next-week')->cron('0 10 * * 5');
    
        $schedule->command('orders:archive-completed-orders')->cron('0 0 * * 3');
    }
}
