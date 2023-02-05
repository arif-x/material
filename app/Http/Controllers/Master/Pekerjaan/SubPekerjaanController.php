<?php

namespace App\Http\Controllers\Master\Pekerjaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterSubPekerjaan;
use App\Models\MasterPekerjaan;
use App\Models\HargaKomponenJasa;
use App\Models\HargaKomponenMaterial;

class SubPekerjaanController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = MasterSubPekerjaan::with('pekerjaan')->orderBy('id', 'desc')->get();
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]['komponen_jasa'] = HargaKomponenJasa::where('sub_pekerjaan_id', $data[$i]['id'])->sum('harga_komponen_jasa');
                $data[$i]['komponen_material'] = HargaKomponenMaterial::where('sub_pekerjaan_id', $data[$i]['id'])->sum('harga_komponen_material');
                $data[$i]['total_komponen'] = $data[$i]['komponen_jasa'] + $data[$i]['komponen_material'];
            }
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }

        $pekerjaan = MasterPekerjaan::pluck('nama_pekerjaan', 'id');
        return view('admin.master.pekerjaan.sub-pekerjaan', compact('pekerjaan'));
    }

    public function single(Request $request, $id){
        if($request->ajax()){
            $data = MasterSubPekerjaan::with('pekerjaan')->orderBy('id', 'desc')->get();
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]['komponen_jasa'] = HargaKomponenJasa::where('sub_pekerjaan_id', $data[$i]['id'])->sum('harga_komponen_jasa');
                $data[$i]['komponen_material'] = HargaKomponenMaterial::where('sub_pekerjaan_id', $data[$i]['id'])->sum('harga_komponen_material');
                $data[$i]['total_komponen'] = $data[$i]['komponen_jasa'] + $data[$i]['komponen_material'];
            }
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }

        return view('admin.master.pekerjaan.sub-pekerjaan-single');
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
