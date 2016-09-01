<?php

namespace App\Console;

use App\Console\Commands\ArchiveCompletedOrders;
use App\Console\Commands\GenerateReportsForCurrentWeek;
use App\Console\Commands\GenerateTmplOrders;
use App\Console\Commands\ProcessPaidOrdersForCurrentWeek;
use App\Console\Commands\ProcessTmplOrdersForCurrentWeek;
use App\Console\Commands\RemoveUnsuccessfulOrdersForCurrentWeek;
use App\Console\Commands\UpdateSearchIndex;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
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
        GenerateTmplOrders::class,
        ProcessTmplOrdersForCurrentWeek::class,
        ProcessPaidOrdersForCurrentWeek::class,
        RemoveUnsuccessfulOrdersForCurrentWeek::class,
        GenerateReportsForCurrentWeek::class,
        ArchiveCompletedOrders::class,
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
        $schedule->command('orders:generate-tmpl-orders')
            ->cron('0 0 * * 2');
        
        $schedule->command('orders:process-tmp-orders-for-current-week')
            ->cron('0 0 * * 2');
        
        $schedule->command('orders:process-paid-orders-for-current-week')
            ->cron($this->_getProcessPaidOrdersTime());
    
        $schedule->command('orders:remove-unsuccessful-orders-for-current-week')
            ->cron($this->_getFinalisingOrdersTime());
        
        $schedule->command('orders:generate-reports-for-current-week')
            ->cron($this->_getFinalisingOrdersTime());
        
        $schedule->command('orders:archive-completed-orders')
            ->cron('0 0 * * 1,2');
    }
    
    /**
     * @return string
     */
    private function _getProcessPaidOrdersTime()
    {
        $time = variable('stop_ordering_time');
        $time = explode(':', $time);
        
        $time = (int) $time[1].' '.(int) $time[0].' * * '.variable('stop_ordering_date');
        
        return $time;
    }
    
    /**
     * @return string
     */
    private function _getFinalisingOrdersTime()
    {
        $time = variable('finalising_reports_time');
        $time = explode(':', $time);
    
        $time = (int) $time[1].' '.(int) $time[0].' * * '.variable('finalising_reports_date');
    
        return $time;
    }
}
