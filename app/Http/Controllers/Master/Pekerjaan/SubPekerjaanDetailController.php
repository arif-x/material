<?php

namespace App\Http\Controllers\Master\Pekerjaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterSubPekerjaan;
use App\Models\HargaKomponenJasa;
use App\Models\HargaKomponenMaterial;
use App\Models\MasterJasa;
use App\Models\MasterMaterial;

class SubPekerjaanDetailController extends Controller
{
    public function index($id){
        $data = MasterSubPekerjaan::findOrFail($id);
        $nama_jasa = MasterJasa::pluck('nama_jasa', 'id');
        $nama_material = MasterMaterial::pluck('nama_material', 'id');
        return view('admin.master.pekerjaan.sub-pekerjaan-detail', compact('data', 'nama_jasa', 'nama_material'));
    }

    public function material($id){
        // $data = MasterSubPekerjaan::with(['harga_satuan_material'])->find($id);
        $data = HargaKomponenMaterial::with(['material'])->where('sub_pekerjaan_id', $id)->get();
        return datatables()->of($data)->addIndexColumn()->toJson();
    }

    public function jasa($id){
        // $data = MasterSubPekerjaan::with(['harga_satuan_jasa'])->find($id);
        $data = HargaKomponenJasa::with(['jasa'])->where('sub_pekerjaan_id', $id)->get();
        return datatables()->of($data)->addIndexColumn()->toJson();
    }
}
