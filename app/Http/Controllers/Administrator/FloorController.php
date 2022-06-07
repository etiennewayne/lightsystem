<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Floor;

class FloorController extends Controller
{
    //


    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');

    }

    public function index(){
        return view('panel.floor');
    }

    public function show($id){
        return Floor::find($id);
    }

     public function getFloors(Request $req){
         $sort = explode('.', $req->sort_by);


        return Floor::where('floor_name', 'like', $req->floor . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function store(Request $req){

        $req->validate([
            'floor_name' => ['required', 'unique:floors']
        ]);

        Floor::create([
            'floor_name' => strtoupper($req->floor_name)
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){

        $req->validate([
            'floor_name' => ['required', 'unique:floors,floor_name,'. $id . ',floor_id']
        ]);

        $data = Floor::find($id);
        $data->floor_name = strtoupper($req->floor_name);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){

        Floor::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);

    }


}
