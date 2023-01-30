<?php

namespace App\Http\Controllers\Master\Pekerjaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterPekerjaan;

class PekerjaanController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = MasterPekerjaan::orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }

        return view('admin.master.pekerjaan.pekerjaan');
    }

    public function show($id){
        $data = MasterPekerjaan::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = MasterPekerjaan::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'kode_pekerjaan' => $request->kode_pekerjaan,
                'nama_pekerjaan' => $request->nama_pekerjaan,
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = MasterPekerjaan::find($id)->delete();
        return response()->json($data);
    }
}
