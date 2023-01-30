<?php

namespace App\Http\Controllers\Master\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SatuanMaterial;

class SatuanMaterialController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = SatuanMaterial::orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }

        return view('admin.master.material.satuan-material');
    }

    public function show($id){
        $data = SatuanMaterial::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = SatuanMaterial::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'satuan_material' => $request->satuan_material
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = SatuanMaterial::find($id)->delete();
        return response()->json($data);
    }
}
