<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;


class Kernel extends ConsoleKernel
{

    protected $commands = [
        \App\Console\Commands\StartFirebaseListener::class,
    ];


    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    // protected function schedule(Schedule $schedule)
    // {
    //     // Call the method to listen for new RFID tags every minute
    //     $schedule->call(function () {
    //         try {
    //             app()->make(\App\Http\Controllers\Firebase\animalDataController::class)->listenForNewTags();
    //         }
    //         catch (\Exception $e) {
    //             Log::error('Error in listenForNewTags: ' . $e->getMessage());
    //         }
    //     })->everyMinute(); // Runs every minute
    // }

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
