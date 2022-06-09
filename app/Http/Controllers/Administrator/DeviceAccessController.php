<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DeviceAccess;
use App\Models\GroupRole;
use App\Models\Syslog;

use Auth;


class DeviceAccessController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');

    }

    public function index(){
        return view('panel.device-access');
    }

    public function show($id){
        return DeviceAccess::with(['device', 'group_role'])
            ->find($id);
    }

     public function getDevicesAccesses(Request $req){
         $sort = explode('.', $req->sort_by);

        return DeviceAccess::with(['device', 'group_role'])
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function store(Request $req){

        $req->validate([
            'device' => ['required'],
            'group_role' => ['required']
        ]);

        $data = DeviceAccess::create([
            'device_id' => $req->device,
            'group_role_id' => $req->group_role
        ]);

        $user = Auth::user();
        Syslog::create([
            'syslog' => 'Data ' .$data->device_access_id .' created.',
            'username' => $user->username,
            'action_type' => 'INSERT'
        ]);


        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){

        $req->validate([
            'device' => ['required'],
            'group_role' => ['required']
        ]);

        $data = DeviceAccess::find($id);
        $data->device_id = $req->device;
        $data->group_role_id = $req->group_role;
        $data->save();

        $user = Auth::user();
        Syslog::create([
            'syslog' => 'Data ' .$id .' updated.',
            'username' => $user->username,
            'action_type' => 'UPDATE'
        ]);

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){

        $data = DeviceAccess::find($id);

        DeviceAccess::destroy($id);


        $user = Auth::user();
        Syslog::create([
            'syslog' => 'Data ' .$data->device_access_id .' delete.',
            'username' => $user->username,
            'action_type' => 'DELETE'
        ]);


        return response()->json([
            'status' => 'deleted'
        ], 200);

    }


}
