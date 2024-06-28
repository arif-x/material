<?php

namespace App\Http\Controllers\Proyek\HargaKomponen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\ProyekHargaKomponenMaterial;

class HargaKomponenMaterialController extends Controller
{
    public function index(){

    }

    public function show($id){
        $data = ProyekHargaKomponenMaterial::with(['sub_pekerjaan', 'material'])->find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        try {
            if ($request->id) {
                $validation = Validator::make($request->all(), [
                    'proyek_sub_pekerjaan_id' => ['required'],
                    'material_id' => [
                        'required', 
                        Rule::unique('proyek_harga_komponen_materials')->where(function ($query) use ($request) {
                            return $query->where('proyek_sub_pekerjaan_id', $request->proyek_sub_pekerjaan_id);
                        })->ignore($request->id)
                    ],
                    'koefisien' => ['required'],
                    'harga_asli' => ['required']
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
                    'proyek_sub_pekerjaan_id' => ['required'],
                    'material_id' => [
                        'required', 
                        Rule::unique('proyek_harga_komponen_materials')->where(function ($query) use ($request) {
                            return $query->where('proyek_sub_pekerjaan_id', $request->proyek_sub_pekerjaan_id);
                        })
                    ],
                    'koefisien' => ['required'],
                    'harga_asli' => ['required']
                ]);
            
                if ($validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation errors',
                        'data' => $validation->errors()
                    ], 422);
                }
            }

            $data = ProyekHargaKomponenMaterial::updateOrCreate(
                [
                    'proyek_sub_pekerjaan_id' => $request->proyek_sub_pekerjaan_id,
                    'material_id' => $request->material_id
                ],
                [
                    'proyek_sub_pekerjaan_id' => $request->proyek_sub_pekerjaan_id,
                    'material_id' => $request->material_id,
                    'harga_asli' => $request->harga_asli,
                    'koefisien' => $request->koefisien,
                    'profit' => 0,
                    'harga_fix' => $request->harga_fix,
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
        $data = ProyekHargaKomponenMaterial::find($id)->delete();
        return response()->json($data);
    }
}
