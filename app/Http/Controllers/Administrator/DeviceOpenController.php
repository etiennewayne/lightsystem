<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;


use App\Models\Device;
use App\Models\Building;

use App\Http\Controllers\Controller;

class DeviceOpenController extends Controller
{
    //


    public function loadSwitchBuildings(Request $req){

        return Building::with(['device'])
            ->whereHas('device', function ($q) use ($req) {
                $q->where('device_name', 'like', $req->device . '%');
            })
            ->get();
    }


}
