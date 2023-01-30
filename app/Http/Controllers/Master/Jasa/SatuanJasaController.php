<?php

namespace App\Http\Controllers\Master\Jasa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SatuanJasa;

class SatuanJasaController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = SatuanJasa::orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }

        return view('admin.master.jasa.satuan-jasa');
    }

    public function show($id){
        $data = SatuanJasa::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = SatuanJasa::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'satuan_jasa' => $request->satuan_jasa
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = SatuanJasa::find($id)->delete();
        return response()->json($data);
    }
}
