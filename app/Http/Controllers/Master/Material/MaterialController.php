<?php

namespace App\Http\Controllers\Master\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisMaterial;
use App\Models\SatuanMaterial;
use App\Models\MasterMaterial;

class MaterialController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = MasterMaterial::with(['satuan', 'jenis'])->orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }
        $jenis_material = JenisMaterial::pluck('jenis_material', 'id');
        $satuan_material = SatuanMaterial::pluck('satuan_material', 'id');
        return view('admin.master.material.material', compact('jenis_material', 'satuan_material'));
    }

    public function show($id){
        $data = MasterMaterial::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = MasterMaterial::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'kode_material' => $request->kode_material,
                'nama_material' => $request->nama_material,
                'jenis_material_id' => $request->jenis_material_id,
                'satuan_material_id' => $request->satuan_material_id,
                'harga_beli' => str_replace(array(',','Rp'), '',$request->harga_beli),
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = MasterMaterial::find($id)->delete();
        return response()->json($data);
    }
}
