<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command as ConsoleCommand;
use Log;

/**
 * Class Command
 * @package App\Console\Commands
 */
class Command extends ConsoleCommand
{
    
    /**
     * @param string $message
     * @param string $status
     * @param mixed  $data
     */
    public function log($message, $status = 'info', $data = [])
    {
        $message = '['.Carbon::now()->toDateTimeString().']: '.$message;
        
        Log::debug($status.' = '.$message, $data);
        
        $this->{$status}($message);
    }
}
