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
        $req->validate([
            'device' => ['required'],
            'schedule_name' => ['required'],
            'date_time' => ['required'],
            'system_action' => ['required'],
            'action_type' => ['required'],
        ]);

        $dateTime =  $req->date_time;
        $nDateTime = date("Y-m-d H:i:s", strtotime($dateTime)); //convert to date format UNIX

        $user = Auth::user();

        $data = Schedule::create([
            'device_id' => $req->device,
            'schedule_name' => strtoupper($req->schedule_name),
            'date_time' => $nDateTime,
            'system_action' => $req->system_action,
            'action_type' => $req->action_type,
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

        $req->validate([
            'device' => ['required'],
            'schedule_name' => ['required'],
            'date_time' => ['required'],
            'system_action' => ['required'],
            'action_type' => ['required'],
        ]);

        $dateTime =  $req->date_time;
        $nDateTime = date("Y-m-d H:i:s", strtotime($dateTime)); //convert to date format UNIX

        $user = Auth::user();

        $data = Schedule::find($id);
        $data->device_id  = $req->device;
        $data->schedule_name  = $req->schedule_name;
        $data->date_time = $nDateTime;
        $data->system_action = $req->system_action;
        $data->action_type = $req->action_type;
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
