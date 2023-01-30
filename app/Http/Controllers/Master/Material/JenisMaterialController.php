<?php

namespace App\Http\Controllers\Master\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisMaterial;

class JenisMaterialController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = JenisMaterial::orderBy('id', 'desc')->get();
            return datatables()->of($data)->addIndexColumn()->toJson();   
        }

        return view('admin.master.material.jenis-material');
    }

    public function show($id){
        $data = JenisMaterial::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = JenisMaterial::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'jenis_material' => $request->jenis_material
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = JenisMaterial::find($id)->delete();
        return response()->json($data);
    }
}
