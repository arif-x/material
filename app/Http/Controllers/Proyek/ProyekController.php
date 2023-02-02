<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;

class ProyekController extends Controller
{
    public function index(Request $request){
        $data = Proyek::orderBy('id', 'desc')->get();
        if($request->ajax()){
            return datatables()->of($data)->addIndexColumn()->toJson();
        }

        return view('admin.proyek.proyek');
    }

    public function show($id){
        $data = Proyek::find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = Proyek::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'nama_proyek' => $request->nama_proyek
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = Proyek::find($id)->delete();
        return response()->json($data);
    }
}
