<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProyekSubPekerjaan;
use App\Models\ProyekHargaKomponenJasa;
use App\Models\ProyekHargaKomponenMaterial;
use App\Models\MasterSubPekerjaan;
use App\Models\MasterMaterial;
use App\Models\MasterJasa;

class ProyekSubPekerjaanDetailController extends Controller
{
    public function jasaDatatable($id){
        $data = ProyekHargaKomponenJasa::with([
            'sub_pekerjaan' => function($query){
                return $query->with(['sub_pekerjaan']);
            },
            'jasa'
        ])->where('proyek_sub_pekerjaan_id', $id)->orderBy('id', 'desc')->get();
        return datatables()->of($data)->addIndexColumn()->toJson();
    }

    public function materialDatatable($id){
        $data = ProyekHargaKomponenMaterial::with([
            'sub_pekerjaan' => function($query){
                return $query->with(['sub_pekerjaan']);
            },
            'material'
        ])->where('proyek_sub_pekerjaan_id', $id)->orderBy('id', 'desc')->get();
        return datatables()->of($data)->addIndexColumn()->toJson();
    }

    public function index($id){
        $data = ProyekSubPekerjaan::with([
            'sub_pekerjaan'=>function($query){return $query->with(['pekerjaan']);},
            'pekerjaan'=>function($query){return $query->with(['proyek']);},
        ])->findOrFail($id);
        // return response()->json($data);
        $nama_jasa = MasterJasa::pluck('nama_jasa', 'id');
        $nama_material = MasterMaterial::pluck('nama_material', 'id');
        return view('admin.proyek.sub-pekerjaan-detail', compact('data', 'nama_jasa', 'nama_material'));
    }
}
