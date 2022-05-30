<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        
        $schedule->call(function () {

            $sched = DB::table('schedules as a')
                ->join('devices as b', 'a.device_id', 'b.device_id')
                ->whereTime('date_time', date("H:i"))
                ->get();

            foreach($sched as $i){
                
                if($i->action_type == 'SPECIFIC'){

                    if(date('Y-m-d H:i') == date('Y-m-d H:i', strtotime($i->date_time))){
                        //SPECIFIC DATE AND TIME SCHEDULE
                        if($i->system_action == 'ON'){
                            Http::withHeaders([
                                'Content-Type' => 'text/plain'
                            ])->get('http://'.$i->device_ip . '/' . $i->device_token_on, []);
                        }else{
                            Http::withHeaders([
                                'Content-Type' => 'text/plain'
                            ])->get('http://'.$i->device_ip . '/' . $i->device_token_off, []); 
                        }
                    }
                }else{
                    //ELSE IF SET TO REPEAT (DAILY)
                    //check if ON action or OFF action
                    if($i->system_action == 'ON'){
                        Http::withHeaders([
                            'Content-Type' => 'text/plain'
                        ])->get('http://'.$i->device_ip . '/' . $i->device_token_on, []);
                    }else{
                        Http::withHeaders([
                            'Content-Type' => 'text/plain'
                        ])->get('http://'.$i->device_ip . '/' . $i->device_token_off, []);  
                    }
                }
            }//foreach
            

        })->everyMinute(); //loop everyminute


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
