<?php

namespace App\Http\Controllers\Master\HargaKomponen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\MasterJasa;
use App\Models\MasterSubPekerjaan;
use App\Models\HargaKomponenJasa;
use App\Models\HargaKomponenMaterial;

class HargaKomponenJasaController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = HargaKomponenJasa::with(['jasa', 'sub_pekerjaan'])->orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }
        $nama_jasa = MasterJasa::pluck('nama_jasa', 'id');
        $nama_sub_pekerjaan = MasterSubPekerjaan::pluck('nama_sub_pekerjaan', 'id');
        return view('admin.master.harga-komponen.jasa', compact('nama_jasa', 'nama_sub_pekerjaan'));
    }

    public function show($id){
        $data = HargaKomponenJasa::with(['jasa'])->find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        try {
            if ($request->id) {
                $validation = Validator::make($request->all(), [
                    'sub_pekerjaan_id' => ['required'],
                    'jasa_id' => [
                        'required', 
                        Rule::unique('harga_komponen_jasas')->where(function ($query) use ($request) {
                            return $query->where('sub_pekerjaan_id', $request->sub_pekerjaan_id);
                        })->ignore($request->id)
                    ],
                    'koefisien' => ['required'],
                    'harga_komponen_jasa' => ['required']
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
                    'jasa_id' => [
                        'required', 
                        Rule::unique('harga_komponen_jasas')->where(function ($query) use ($request) {
                            return $query->where('sub_pekerjaan_id', $request->sub_pekerjaan_id);
                        })
                    ],
                    'koefisien' => ['required'],
                    'harga_komponen_jasa' => ['required']
                ]);
            
                if ($validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation errors',
                        'data' => $validation->errors()
                    ], 422);
                }
            }

            $data = HargaKomponenJasa::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'jasa_id' => $request->jasa_id,
                    'sub_pekerjaan_id' => $request->sub_pekerjaan_id,
                    'koefisien' => $request->koefisien,
                    'profit' => 0,
                    'harga_komponen_jasa' => $request->harga_komponen_jasa
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
        $data = HargaKomponenJasa::find($id)->delete();
        return response()->json($data);
    }
}
