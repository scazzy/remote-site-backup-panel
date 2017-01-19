<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\RemoteController;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*$jobs = DB::select("
            SELECT s.id, j.site_id, j.frequency, s.site_name, s.ssh_address, s.ssh_username, s.ssh_password, s.ssh_path,
                    s.db_host, s.db_database, s.db_username, s.db_password, s.is_db_backup_enabled
            FROM jobs j
            LEFT JOIN sites s ON j.site_id = s.id
            WHERE s.is_active = 1
        ");

        foreach ($jobs as $job) {
          $schedule->call(function() use($job) {
              if($job->site_id) {
                $rc = new RemoteController((array) $job);
                $rc->doSiteBackup();
              }
          })->cron($job->frequency);
        }*/

    }
}
