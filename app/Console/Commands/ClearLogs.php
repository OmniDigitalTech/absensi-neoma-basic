<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Laravel log files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');

        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
            $this->info('Laravel logs have been cleared!');
        } else {
            $this->info('No log file found to clear.');
        }

        return 0;
    }
}
