<?php

namespace App\Http\Controllers\Master\Pekerjaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SatuanSubPekerjaan;

class SatuanSubPekerjaanController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = SatuanSubPekerjaan::orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();
        }

        return view('admin.master.pekerjaan.satuan-sub-pekerjaan');
    }

    public function show($id){
        $data = SatuanSubPekerjaan::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = SatuanSubPekerjaan::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'satuan_sub_pekerjaan' => $request->satuan_sub_pekerjaan
            ]
        );
        return response()->json($data);
    }

    public function destroy($id){
        $data = SatuanSubPekerjaan::find($id)->delete();
        return response()->json($data);
    }
}
