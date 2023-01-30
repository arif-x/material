<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterMaterial;
use App\Models\MasterJasa;

class GetHargaController extends Controller
{
    public function material($id){
        $data = MasterMaterial::find($id);
        return response()->json($data);
    }

    public function jasa($id){
        $data = MasterJasa::find($id);
        return response()->json($data);
    }
}
