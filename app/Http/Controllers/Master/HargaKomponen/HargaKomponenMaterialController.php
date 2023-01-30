<?php

namespace App\Http\Controllers\Master\HargaKomponen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterMaterial;
use App\Models\MasterSubPekerjaan;
use App\Models\HargaKomponenMaterial;
use App\Models\HargaKomponenJasa;

class HargaKomponenMaterialController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = HargaKomponenMaterial::with(['material', 'sub_pekerjaan'])->orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }
        $nama_material = MasterMaterial::pluck('nama_material', 'id');
        $nama_sub_pekerjaan = MasterSubPekerjaan::pluck('nama_sub_pekerjaan', 'id');
        return view('admin.master.harga-komponen.material', compact('nama_material', 'nama_sub_pekerjaan'));
    }

    public function show($id){
        $data = HargaKomponenMaterial::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = HargaKomponenMaterial::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'material_id' => $request->material_id,
                'sub_pekerjaan_id' => $request->sub_pekerjaan_id,
                'koefisien' => $request->koefisien,
                'harga_komponen_material' => $request->harga_komponen_material
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = HargaKomponenMaterial::find($id)->delete();
        return response()->json($data);
    }
}
