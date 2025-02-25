<?php

namespace App\Http\Controllers\Master\HargaKomponen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        $data = HargaKomponenMaterial::with(['material'])->find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        try {
            if ($request->id) {
                $validation = Validator::make($request->all(), [
                    'sub_pekerjaan_id' => ['required'],
                    'material_id' => [
                        'required', 
                        Rule::unique('harga_komponen_materials')->where(function ($query) use ($request) {
                            return $query->where('sub_pekerjaan_id', $request->sub_pekerjaan_id);
                        })->ignore($request->id)
                    ],
                    'koefisien' => ['required'],
                    'harga_komponen_material' => ['required']
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
                    'sub_pekerjaan_id' => ['required'],
                    'material_id' => [
                        'required', 
                        Rule::unique('harga_komponen_materials')->where(function ($query) use ($request) {
                            return $query->where('sub_pekerjaan_id', $request->sub_pekerjaan_id);
                        })
                    ],
                    'koefisien' => ['required'],
                    'harga_komponen_material' => ['required']
                ]);
            
                if ($validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation errors',
                        'data' => $validation->errors()
                    ], 422);
                }
            }

            $data = HargaKomponenMaterial::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'material_id' => $request->material_id,
                    'sub_pekerjaan_id' => $request->sub_pekerjaan_id,
                    'koefisien' => $request->koefisien,
                    'profit' => 0,
                    'harga_komponen_material' => $request->harga_komponen_material
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
        $data = HargaKomponenMaterial::find($id)->delete();
        return response()->json($data);
    }
}
