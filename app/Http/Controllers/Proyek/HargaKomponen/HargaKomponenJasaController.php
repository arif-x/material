<?php

namespace App\Http\Controllers\Proyek\HargaKomponen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProyekHargaKomponenJasa;

class HargaKomponenJasaController extends Controller
{
    public function index(){

    }

    public function show($id){
        $data = ProyekHargaKomponenJasa::with(['sub_pekerjaan', 'jasa'])->find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = ProyekHargaKomponenJasa::updateOrCreate(
            [
                'proyek_sub_pekerjaan_id' => $request->proyek_sub_pekerjaan_id,
                'jasa_id' => $request->jasa_id
            ],
            [
                'proyek_sub_pekerjaan_id' => $request->proyek_sub_pekerjaan_id,
                'jasa_id' => $request->jasa_id,
                'harga_asli' => $request->harga_asli,
                'koefisien' => $request->koefisien,
                'harga_fix' => $request->harga_fix,
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = ProyekHargaKomponenJasa::find($id)->delete();
        return response()->json($data);
    }
}
