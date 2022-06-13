<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Schedule;
use App\Models\Syslog;

use Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
       
    }
    
    public function index(){
        return view('panel.schedule');
    }

    public function getSchedules(Request $req){
        $sort = explode('.', $req->sort_by);

        $dateFrom =  $req->date_from;
        $nDateFrom = date("Y-m-d", strtotime($dateFrom)); //convert to date format UNIX

        $dateTo = $req->date_to;
        $nDateTo = date('H:i:s',strtotime($dateTo)); //convert to format time UNIX


        $data = Schedule::with(['device'])
            //->whereBetween('date_from', [$nDateFrom, $nDateTo])
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
        return $data;
    }

    public function show($id){
        return Schedule::find($id);
    }

    public function create(){
        return view('panel.schedule-create-update')
            ->with('id', 0);
    }

    public function store(Request $req){
        //return $req;

        if($req->optionday == 0){
            $req->validate([
                'device' => ['required'],
                'schedule_name' => ['required'],
                 'date_time' => ['required'],
                // 'system_action' => ['required'],
                // 'action_type' => ['required'],
                //'schedule_on' => ['required'],
                //'schedule_off' => ['required'],
            ]);
        }else{
            $req->validate([
                'device' => ['required'],
                'schedule_name' => ['required'],
                //'date_time' => ['required'],
                // 'system_action' => ['required'],
                // 'action_type' => ['required'],
                'schedule_on' => ['required'],
                'schedule_off' => ['required'],
            ]);
        }
        
        if($req->optionday == 0){
            $dateTime =  $req->date_time;
            $nDateTime = date("Y-m-d H:i:s", strtotime($dateTime)); //convert to date format UNIX
        }else{
            $scheduleOn = date("H:i:s", strtotime($req->schedule_on)); //convert to date format UNIX
            $scheduleOff = date("H:i:s", strtotime($req->schedule_off)); //convert to date format UNIX
        }
        
        $user = Auth::user();

        $data = Schedule::create([
            'device_id' => $req->device,
            'schedule_name' => strtoupper($req->schedule_name),
            'date_time' => $req->optionday == 0 ? $nDateTime : null,
            'system_action' => $req->optionday == 0 ? $req->system_action : null,
            // 'action_type' => $req->action_type,
            'schedule_on' => $req->optionday == 1 ? $scheduleOn : null,
            'schedule_off' => $req->optionday == 1 ? $scheduleOff : null,
            'mon' => $req->mon,
            'tue' => $req->tue,
            'wed' => $req->wed,
            'thur' => $req->thur,
            'fri' => $req->fri,
            'sat' => $req->sat,
            'sun' => $req->sun,
            'ntuser' => $user->username,
        ]);


        Syslog::create([
            'syslog' => 'Schedule ' .$data->schedule_id . '('. $data->schedule_name.') created.',
            'username' => $user->username
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function edit($id){
        return view('panel.schedule-create-update')
            ->with('id', $id);
    }
    public function update(Request $req, $id){

        if($req->optionday == 0){
            $req->validate([
                'device' => ['required'],
                'schedule_name' => ['required'],
                 'date_time' => ['required'],
                // 'system_action' => ['required'],
                // 'action_type' => ['required'],
                //'schedule_on' => ['required'],
                //'schedule_off' => ['required'],
            ]);
        }else{
            $req->validate([
                'device' => ['required'],
                'schedule_name' => ['required'],
                //'date_time' => ['required'],
                // 'system_action' => ['required'],
                // 'action_type' => ['required'],
                'schedule_on' => ['required'],
                'schedule_off' => ['required'],
            ]);
        }

        if($req->optionday == 0){
            $dateTime =  $req->date_time;
            $nDateTime = date("Y-m-d H:i:s", strtotime($dateTime)); //convert to date format UNIX
        }else{
            $scheduleOn = date("H:i:s", strtotime($req->schedule_on)); //convert to date format UNIX
            $scheduleOff = date("H:i:s", strtotime($req->schedule_off)); //convert to date format UNIX
        }
        $user = Auth::user();

        $data = Schedule::find($id);
        $data->device_id  = $req->device;
        $data->schedule_name  = $req->schedule_name;
        $data->date_time = $req->optionday == 0 ? $nDateTime : null;
        $data->system_action = $req->optionday == 0 ? $req->system_action : null;
        // $data->action_type = $req->action_type;
        $data->schedule_on = $req->optionday == 1 ? $scheduleOn : null;
        $data->schedule_off = $req->optionday == 1 ? $scheduleOff : null;

        $data->mon = $req->mon;
        $data->tue = $req->tue;
        $data->wed = $req->wed;
        $data->thur = $req->thur;
        $data->fri = $req->fri;
        $data->sat = $req->sat;
        $data->sun = $req->sun;
        $data->ntuser = $user->username;
        $data->save();

        Syslog::create([
            'syslog' => 'Schedule ' .$data->schedule_id . '('. $data->schedule_name.') updated.',
            'username' => $user->username
        ]);

        return response()->json([
            'status' => 'updated'
        ],200);
    }


    public function destroy($id){
        $data = Schedule::find($id);
        Schedule::destroy($id);

        $user = Auth::user();
        Syslog::create([
            'syslog' => 'Schedule ' .$data->schedule_id . '('. $data->schedule_name.') deleted.',
            'username' => $user->username
        ]);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

}
