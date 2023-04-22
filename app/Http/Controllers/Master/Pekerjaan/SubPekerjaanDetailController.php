<?php

namespace App\Http\Controllers\Master\Pekerjaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterSubPekerjaan;
use App\Models\HargaKomponenJasa;
use App\Models\HargaKomponenMaterial;
use App\Models\SatuanSubPekerjaan;
use App\Models\MasterJasa;
use App\Models\MasterMaterial;

class SubPekerjaanDetailController extends Controller
{
    public function index($id){
        $data = MasterSubPekerjaan::findOrFail($id);
        $nama_jasa = MasterJasa::get(['nama_jasa', 'kode_jasa', 'id']);
        $nama_material = MasterMaterial::get(['nama_material', 'kode_material', 'id']);
        $satuan_sub_pekerjaan = SatuanSubPekerjaan::pluck('satuan_sub_pekerjaan', 'id');
        return view('admin.master.pekerjaan.sub-pekerjaan-detail', compact('data', 'nama_jasa', 'nama_material', 'satuan_sub_pekerjaan'));
    }

    public function materialDatatable($id){
        // $data = MasterSubPekerjaan::with(['harga_satuan_material'])->find($id);
        $data = HargaKomponenMaterial::with(['material'])->where('sub_pekerjaan_id', $id)->get();
        return datatables()->of($data)->addIndexColumn()->toJson();
    }

    public function jasaDatatable($id){
        // $data = MasterSubPekerjaan::with(['harga_satuan_jasa'])->find($id);
        $data = HargaKomponenJasa::with(['jasa'])->where('sub_pekerjaan_id', $id)->get();
        return datatables()->of($data)->addIndexColumn()->toJson();
    }
}
