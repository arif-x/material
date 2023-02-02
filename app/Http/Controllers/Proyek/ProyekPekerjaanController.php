<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\ProyekPekerjaan;
use App\Models\MasterPekerjaan;
use App\Models\MasterSubPekerjaan;

class ProyekPekerjaanController extends Controller
{
    public function datatable($id){
        $data = ProyekPekerjaan::orderBy('id', 'desc')->get();
        return datatables()->of($data)->addIndexColumn()->toJson();
    }

    public function index($id){
        $proyek = Proyek::findOrFail($id);
        return view('admin.proyek.pekerjaan', compact('proyek'));
    }

    public function getSubPekerjaan($id){
        $data = MasterSubPekerjaan::where('pekerjaan_id', $id)->get();
        return response()->json($data);
    }

    public function form($id){
        $proyek = Proyek::findOrFail($id);
        $pekerjaan = MasterPekerjaan::pluck('id', 'nama_pekerjaan');
        return view('admin.proyek.pekerjaan-form', compact('proyek', 'pekerjaan'));
    }

    public function show($id){
        $data = ProyekPekerjaan::find($id);
        return response()->json($data);
    }

    public function store(){

    }

    public function destroy($id){
        $data = ProyekPekerjaan::find($id)->delete();
        return response()->json($data);
    }
}
