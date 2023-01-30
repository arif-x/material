<?php

namespace App\Http\Controllers\Master\Jasa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisJasa;

class JenisJasaController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = JenisJasa::orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }

        return view('admin.master.jasa.jenis-jasa');
    }

    public function show($id){
        $data = JenisJasa::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = JenisJasa::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'jenis_jasa' => $request->jenis_jasa
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = JenisJasa::find($id)->delete();
        return response()->json($data);
    }
}
