<?php

namespace App\Http\Controllers\Master\Pekerjaan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\MasterPekerjaan;

class PekerjaanController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = MasterPekerjaan::orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }

        return view('admin.master.pekerjaan.pekerjaan');
    }

    public function show($id){
        $data = MasterPekerjaan::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        try {
            if($request->id) {
                $validation = Validator::make($request->all(), [
                    'kode_pekerjaan' => ['required', 'unique:master_pekerjaans,id,'.$request->id],
                    'nama_pekerjaan' => ['required']
                ]);
            } else {
                $validation = Validator::make($request->all(), [
                    'kode_pekerjaan' => ['required', 'unique:master_pekerjaans'],
                    'nama_pekerjaan' => ['required']
                ]);
            }
        
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation errors',
                    'data' => $validation->errors()
                ], 422);
            }

            $data = MasterPekerjaan::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'kode_pekerjaan' => $request->kode_pekerjaan,
                    'nama_pekerjaan' => $request->nama_pekerjaan,
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
        $data = MasterPekerjaan::find($id)->delete();
        return response()->json($data);
    }
}
