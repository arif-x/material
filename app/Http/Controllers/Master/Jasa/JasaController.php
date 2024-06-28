<?php

namespace App\Http\Controllers\Master\Jasa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\JenisJasa;
use App\Models\SatuanJasa;
use App\Models\MasterJasa;

class JasaController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = MasterJasa::with(['satuan', 'jenis'])->orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }
        $jenis_jasa = JenisJasa::pluck('jenis_jasa', 'id');
        $satuan_jasa = SatuanJasa::pluck('satuan_jasa', 'id');
        return view('admin.master.jasa.jasa', compact('jenis_jasa', 'satuan_jasa'));
    }

    public function show($id){
        $data = MasterJasa::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        try {
            if($request->id) {
                $validation = Validator::make($request->all(), [
                    'kode_jasa' => ['required', 'unique:master_jasas,id,'.$request->id],
                    'nama_jasa' => ['required'],
                    'jenis_jasa_id' => ['required'],
                    'satuan_jasa_id' => ['required'],
                    'harga_jasa' => ['required']
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
                    'kode_jasa' => ['required', 'unique:master_jasas'],
                    'nama_jasa' => ['required'],
                    'jenis_jasa_id' => ['required'],
                    'satuan_jasa_id' => ['required'],
                    'harga_jasa' => ['required']
                ]);
            
                if ($validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation errors',
                        'data' => $validation->errors()
                    ], 422);
                }
            }

            $data = MasterJasa::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'kode_jasa' => $request->kode_jasa,
                    'nama_jasa' => $request->nama_jasa,
                    'jenis_jasa_id' => $request->jenis_jasa_id,
                    'satuan_jasa_id' => $request->satuan_jasa_id,
                    'harga_jasa' => str_replace(array(',','Rp'), '',$request->harga_jasa),
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
        $data = MasterJasa::find($id)->delete();
        return response()->json($data);
    }
}