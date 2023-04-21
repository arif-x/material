<?php

namespace App\Http\Controllers\Proyek\HargaKomponen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProyekHargaKomponenMaterial;

class HargaKomponenMaterialController extends Controller
{
    public function index(){

    }

    public function show($id){
        $data = ProyekHargaKomponenMaterial::with(['sub_pekerjaan', 'material'])->find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = ProyekHargaKomponenMaterial::updateOrCreate(
            [
                'proyek_sub_pekerjaan_id' => $request->proyek_sub_pekerjaan_id,
                'material_id' => $request->material_id
            ],
            [
                'proyek_sub_pekerjaan_id' => $request->proyek_sub_pekerjaan_id,
                'material_id' => $request->material_id,
                'harga_asli' => $request->harga_asli,
                'koefisien' => $request->koefisien,
                'profit' => 0,
                'harga_fix' => $request->harga_fix,
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = ProyekHargaKomponenMaterial::find($id)->delete();
        return response()->json($data);
    }
}
