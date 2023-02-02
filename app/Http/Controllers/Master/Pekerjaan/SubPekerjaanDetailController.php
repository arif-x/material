<?php

namespace App\Http\Controllers\Master\Pekerjaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterSubPekerjaan;
use App\Models\HargaKomponenJasa;
use App\Models\HargaKomponenMaterial;

class SubPekerjaanDetailController extends Controller
{
    public function index($id){
        $data = MasterSubPekerjaan::findOrFail($id);
        return view('admin.master.pekerjaan.sub-pekerjaan-detail', compact('data'));
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
