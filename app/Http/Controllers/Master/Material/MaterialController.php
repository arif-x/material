<?php

namespace App\Http\Controllers\Master\Material;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
        try {
            if($request->id) {
                $validation = Validator::make($request->all(), [
                    'kode_material' => ['required', 'unique:master_materials,id,'.$request->id],
                    'nama_material' => ['required'],
                    'jenis_material_id' => ['required'],
                    'satuan_material_id' => ['required'],
                    'harga_beli' => ['required']
                ]);
            
                if ($validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation errors',
                        'data' => $validation->errors()
                    ], 422);
                }
            } else {
                $validation = Validator::make($request->all(), [
                    'kode_material' => ['required', 'unique:master_materials'],
                    'nama_material' => ['required'],
                    'jenis_material_id' => ['required'],
                    'satuan_material_id' => ['required'],
                    'harga_beli' => ['required']
                ]);
            
                if ($validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation errors',
                        'data' => $validation->errors()
                    ], 422);
                }
            }

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

            return response()->json([
                'status' => true,
                'message' => 'Sukses',
                'data' => $data
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id){
        $data = MasterMaterial::find($id)->delete();
        return response()->json($data);
    }
}
