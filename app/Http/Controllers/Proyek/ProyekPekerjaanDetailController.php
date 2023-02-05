<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\ProyekSubPekerjaan;
use App\Models\ProyekPekerjaan;
use App\Models\MasterPekerjaan;

class ProyekPekerjaanDetailController extends Controller
{
    public function index(Request $request, $id){
        $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->findOrFail($id);
        $proyek = Proyek::findOrFail($pekerjaan->proyek_id);

        return view('admin.proyek.pekerjaan-detail', compact('pekerjaan', 'proyek'));
    }

    public function datatable($pekerjaan_id){
        $data = ProyekSubPekerjaan::with(['sub_pekerjaan'])->where('proyek_pekerjaan_id', $pekerjaan_id)->select('*')->get();
        return datatables()->of($data)->addIndexColumn()->toJson();
    }
}
