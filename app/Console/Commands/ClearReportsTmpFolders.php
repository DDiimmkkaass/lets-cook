<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\OrderService;
use Carbon\Carbon;
use Exception;
use File;

/**
 * Class ClearReportsTmpFolders
 * @package App\Console\Commands
 */
class ClearReportsTmpFolders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:clear-tmp';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear tmp files generated for download reports archive';
    
    /**
     * @var int
     */
    protected $ttl = 300; // in seconds
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->log('Start '.$this->description);
    
        try {
            $this->log('Start deleting xls files, folder: '.config('excel.export.store.path'));
            
            $files = File::allFiles(config('excel.export.store.path'));
        
            foreach ($files as $file) {
                $date = Carbon::createFromTimestamp($file->getCTime());
            
                if ($date->diffInSeconds(Carbon::now()) > $this->ttl) {
                    $this->log('Delete expired file '.$file->getPath().'/'.$file->getFilename());
                
                    File::delete($file);
                }
            }
    
            $this->log('Start deleting archives files, folder: '.config('archive.path'));
            
            $files = File::allFiles(config('archive.path'));
    
            foreach ($files as $file) {
                $date = Carbon::createFromTimestamp($file->getCTime());
        
                if ($date->diffInSeconds(Carbon::now()) > $this->ttl) {
                    $this->log('Delete expired file '.$file->getPath().'/'.$file->getFilename());
            
                    File::delete($file);
                }
            }
        } catch (Exception $e) {
            $message = $e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile();
        
            $this->log($message, [], 'error');
        
            admin_notify($message, ['command' => $this->description]);
        }
    
        $this->log('End '.$this->description);
    }
}
