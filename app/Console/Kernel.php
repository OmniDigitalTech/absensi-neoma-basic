<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ClearLogs::class, // Register your custom ClearLogs command
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('kirim:email')->weekly()->mondays()->at('07:00')->timezone('Asia/Jakarta');
        $schedule->command('kirim:email2')->weekly()->mondays()->at('07:00')->timezone('Asia/Jakarta');
        $schedule->command('reset:cuti')->dailyAt('07:00')->timezone('Asia/Jakarta');
        $schedule->command('create:shift')->monthly()->timezone('Asia/Jakarta');

        // Fetch shifts and dynamically calculate runtimes
        $shifts = \App\Models\Shift::all();

        // Log time for debugging purposes
//        Log::info('Current Timezone Kernel: ' . Carbon::now()->timezoneName);
//        Log::info("Timezone Kernel: " . Carbon::now()->toDateTimeString());

        // get each of shift data
        foreach ($shifts as $shift) {
            // Handle time conversion for `jam_masuk` (shift start) and `jam_keluar` (shift end)
            $hoursIn = str_replace('.', ':', $shift->jam_masuk) . ':00'; // Format jam_masuk to H:i:s
            $hoursOut = str_replace('.', ':', $shift->jam_keluar) . ':00'; // Results in `H:i:s`

            try {
                // Parse `jam_masuk` and `jam_keluar` into Carbon instances
                $hoursInTime = Carbon::createFromFormat('H:i:s', $hoursIn);
                $hoursOutTime = Carbon::createFromFormat('H:i:s', $hoursOut);

                // Calculate 10 minutes before `jam_masuk` for shift start notifications
                $tenMinutesBeforeStart = $hoursInTime->copy()->subMinutes(10);
                // Exact time of `jam_masuk` for shift start notifications
                $exactShiftStart = $hoursInTime->copy();
                // Calculate 10 and 5 minutes before the `hours_out`
                $tenMinutesBeforeEnd = $hoursOutTime->copy()->subMinutes(10); // 10 minutes before `jam_keluar`
                $fiveMinutesBeforeEnd = $hoursOutTime->copy()->subMinutes(5); // 5 minutes before `jam_keluar`
                // Exact time of `jam_keluar` for shift end notifications
                $exactShiftEnd = $hoursOutTime->copy();

                // Log schedules for debugging purposes
//                Log::info("Scheduling Notify Absensi for Shift ID {$shift->id}
//                    10 minutes before start at {$tenMinutesBeforeStart->format('H:i')},
//                    start at {$exactShiftStart->format('H:i')},
//                    10 minute before end at {$tenMinutesBeforeEnd->format('H:i')},
//                    5 minute before end at {$fiveMinutesBeforeEnd->format('H:i')}
//                    and end at {$exactShiftEnd->format('H:i')}");

                // Schedule notification tasks for each timing point
                // 1. Schedule for 10 minutes before `jam_masuk` (Shift Start)
                $schedule->command('notify:absensi')
                    ->dailyAt($tenMinutesBeforeStart->format('H:i'));

                // 2. Schedule for exact time of `jam_masuk` (Shift Start)
                $schedule->command('notify:absensi')
                    ->dailyAt($exactShiftStart->format('H:i'));

                // 3. Schedule for 10 minutes before `jam_keluar` (Shift End)
                $schedule->command('notify:absensi')
                    ->dailyAt($tenMinutesBeforeEnd->format('H:i'));

                // 4. Schedule for 5 minutes before `jam_keluar` (Shift End)
                $schedule->command('notify:absensi')
                    ->dailyAt($fiveMinutesBeforeEnd->format('H:i'));

                // 5. Schedule for exact time of `jam_keluar` (Shift End)
                $schedule->command('notify:absensi')
                    ->dailyAt($exactShiftEnd->format('H:i'));
            } catch (\Exception $e) {
                // Handle invalid times
                Log::error("Invalid jam_keluar format for Shift ID {$shift->id}: " . $shift->jam_keluar);
            }
        }

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
