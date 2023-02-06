<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\ProyekSubPekerjaan;
use App\Models\ProyekPekerjaan;
use App\Models\MasterPekerjaan;
use App\Models\MasterSubPekerjaan;
use App\Models\ProyekHargaKomponenJasa;
use App\Models\ProyekHargaKomponenMaterial;

class ProyekPekerjaanDetailController extends Controller
{
    public function index(Request $request, $id){
        $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->findOrFail($id);
        $sub_pekerjaan = ProyekSubPekerjaan::with(['sub_pekerjaan', 'harga_komponen_jasa', 'harga_komponen_material'])->where('proyek_pekerjaan_id', $id)->get();
        $proyek = Proyek::findOrFail($pekerjaan->proyek_id);
        $sub_pekerjaan = MasterSubPekerjaan::where('pekerjaan_id', $pekerjaan->pekerjaan_id)->pluck('nama_sub_pekerjaan', 'id');
        return view('admin.proyek.pekerjaan-detail', compact('pekerjaan', 'proyek', 'sub_pekerjaan'));
    }

    public function getRincianAjax($id){
        $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->findOrFail($id);
        $sub_pekerjaan = ProyekSubPekerjaan::with(['sub_pekerjaan', 'harga_komponen_jasa', 'harga_komponen_material'])->where('proyek_pekerjaan_id', $id)->get();
        $komponen = [];
        for ($i=0; $i < count($sub_pekerjaan); $i++) { 
            $fix_komponen_jasa = 0;
            $fix_komponen_material = 0;
            for ($j=0; $j < count($sub_pekerjaan[$i]->harga_komponen_jasa); $j++) { 
                $komponen_jasa = $sub_pekerjaan[$i]->harga_komponen_jasa[$j]->harga_fix * $sub_pekerjaan[$i]->volume;
                $fix_komponen_jasa = $fix_komponen_jasa + $komponen_jasa;
            }
            for ($j=0; $j < count($sub_pekerjaan[$i]->harga_komponen_material); $j++) { 
                $komponen_material = $sub_pekerjaan[$i]->harga_komponen_material[$j]->harga_fix * $sub_pekerjaan[$i]->volume;
                $fix_komponen_material = $fix_komponen_material + $komponen_material;
            }
            $arr = [
                'sub_pekerjaan' => $sub_pekerjaan[$i]->sub_pekerjaan->nama_sub_pekerjaan,
                'fix_komponen_jasa' => $fix_komponen_jasa,
                'fix_komponen_material' => $fix_komponen_material,
            ];  
            array_push($komponen, $arr);
        }
        for ($i=0; $i < count($komponen); $i++) { 
            $komponen[$i]['komponen_total'] = $komponen[$i]['fix_komponen_jasa'] + $komponen[$i]['fix_komponen_material'];
        }
        return response()->json($komponen);
    }

    public function datatable($pekerjaan_id){
        $data = ProyekSubPekerjaan::with(['sub_pekerjaan'])->where('proyek_pekerjaan_id', $pekerjaan_id)->select('*')->get();
        return datatables()->of($data)->addIndexColumn()
        ->addColumn('komponen_jasa', function($row){
            return ProyekHargaKomponenJasa::where('proyek_sub_pekerjaan_id', $row->id)->sum('harga_fix');
        })
        ->addColumn('komponen_material', function($row){
            return ProyekHargaKomponenMaterial::where('proyek_sub_pekerjaan_id', $row->id)->sum('harga_fix');
        })
        ->toJson();
    }

    public function show($id){
        $data = ProyekSubPekerjaan::with(['sub_pekerjaan'])->find($id);
        return response()->json($data);
    }

    public function update(Request $request){
        $data = ProyekSubPekerjaan::where('id', $request->id)->update(
            [
                'volume' => $request->volume
            ]
        );

        return response()->json($data);
    }

    public function destroy($id){
        $data = ProyekSubPekerjaan::find($id)->delete();
        return response()->json($data);
    }
}
