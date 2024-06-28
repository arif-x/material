<?php

namespace App\Http\Controllers\Proyek\HargaKomponen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\ProyekHargaKomponenJasa;

class HargaKomponenJasaController extends Controller
{
    public function index(){

    }

    public function show($id){
        $data = ProyekHargaKomponenJasa::with(['sub_pekerjaan', 'jasa'])->find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        try {
            if ($request->id) {
                $validation = Validator::make($request->all(), [
                    'proyek_sub_pekerjaan_id' => ['required'],
                    'jasa_id' => [
                        'required', 
                        Rule::unique('proyek_harga_komponen_jasas')->where(function ($query) use ($request) {
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
                    'jasa_id' => [
                        'required', 
                        Rule::unique('proyek_harga_komponen_jasas')->where(function ($query) use ($request) {
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

            $data = ProyekHargaKomponenJasa::updateOrCreate(
                [
                    'id' => $request->id
                    // 'proyek_sub_pekerjaan_id' => $request->proyek_sub_pekerjaan_id,
                    // 'jasa_id' => $request->jasa_id
                ],
                [
                    'proyek_sub_pekerjaan_id' => $request->proyek_sub_pekerjaan_id,
                    'jasa_id' => $request->jasa_id,
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
        $data = ProyekHargaKomponenJasa::find($id)->delete();
        return response()->json($data);
    }
}
