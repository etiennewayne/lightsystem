<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;


use App\Models\Device;
use App\Http\Controllers\Controller;

class DeviceOpenController extends Controller
{
    //


    public function loadDevices(){
        return Device::orderBy('device_name', 'asc')
            ->get();
    }


}
