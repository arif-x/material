<?php

namespace App\Http\Controllers\Master\Pekerjaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterSubPekerjaan;
use App\Models\MasterPekerjaan;

class SubPekerjaanController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = MasterSubPekerjaan::with('pekerjaan')->orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }

        $pekerjaan = MasterPekerjaan::pluck('nama_pekerjaan', 'id');
        return view('admin.master.pekerjaan.sub-pekerjaan', compact('pekerjaan'));
    }

    public function show($id){
        $data = MasterSubPekerjaan::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = MasterSubPekerjaan::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'pekerjaan_id' => $request->pekerjaan_id,
                'kode_sub_pekerjaan' => $request->kode_sub_pekerjaan,
                'nama_sub_pekerjaan' => $request->nama_sub_pekerjaan,
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = MasterSubPekerjaan::find($id)->delete();
        return response()->json($data);
    }
}
