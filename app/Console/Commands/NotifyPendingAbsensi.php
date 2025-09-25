<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use App\Services\KaryawanService;
use App\Services\NotifyService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyPendingAbsensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:absensi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to employees for pending absensi.';
    protected $notifyService;
    protected $emailService;
    protected $karyawanService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NotifyService $notifyService, EmailService $emailService, KaryawanService $karyawanService)
    {
        parent::__construct();
        $this->notifyService = $notifyService;
        $this->emailService = $emailService;
        $this->karyawanService = $karyawanService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Call the function
        $now = Carbon::now();

        // Log for debugging (can be removed later)
        $this->info("[NotifyPendingAbsensi] Triggered at {$now->toDateTimeString()}");

        // call the function
        (new \App\Http\Controllers\karyawanController($this->notifyService, $this->emailService, $this->karyawanService))->notifyPendingAbsensi();

        return 0;
    }
}
