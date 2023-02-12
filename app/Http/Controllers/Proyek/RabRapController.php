<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RabExport;
use App\Exports\RapExport;
use App\Models\Proyek;
use App\Models\ProyekPekerjaan;
use App\Models\ProyekSubPekerjaan;
use App\Models\ProyekHargaKomponenMaterial;
use App\Models\ProyekHargaKomponenJasa;

class RabRapController extends Controller
{
    public function rab(Request $request, $id){
        // return Excel::download(new RabExport($id, $request->tempat, $request->tanggal, $request->nama, $request->npm), 'rab.xlsx');
        return Excel::download(new RabExport($id), 'rab.xlsx');
    }

    public function rap(Request $request, $id){
        return Excel::download(new RapExport($id), 'rap.xlsx');
    }

    public function rabPreview($id){
        $proyek = Proyek::find($id);
        $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->where('proyek_id', $id)->get();

        $arr_sub = [];
        $data = [];

        foreach ($pekerjaan as $key => $value) {
            $sub_pekerjaan = ProyekSubPekerjaan::with(['sub_pekerjaan' => function($query){return $query->with('satuan_sub_pekerjaan');}, 'harga_komponen_jasa', 'harga_komponen_material'])->where('proyek_pekerjaan_id', $value['id'])->get();
            $komponen = [];
            for ($i=0; $i < count($sub_pekerjaan); $i++) { 
                $fix_komponen_jasa_sum = 0;
                $fix_komponen_material_sum = 0;
                $fix_komponen_jasa = 0;
                $fix_komponen_material = 0;
                for ($j=0; $j < count($sub_pekerjaan[$i]->harga_komponen_jasa); $j++) { 
                    $komponen_jasa = $sub_pekerjaan[$i]->harga_komponen_jasa[$j]->harga_fix * $sub_pekerjaan[$i]->volume;
                    $fix_komponen_jasa = $fix_komponen_jasa + $komponen_jasa;
                    $komp_jasa = $sub_pekerjaan[$i]->harga_komponen_jasa[$j]->harga_fix;
                    $fix_komponen_jasa_sum = $fix_komponen_jasa_sum + $komp_jasa;
                }
                for ($j=0; $j < count($sub_pekerjaan[$i]->harga_komponen_material); $j++) { 
                    $komponen_material = $sub_pekerjaan[$i]->harga_komponen_material[$j]->harga_fix * $sub_pekerjaan[$i]->volume;
                    $fix_komponen_material = $fix_komponen_material + $komponen_material;
                    $komp_material = $sub_pekerjaan[$i]->harga_komponen_material[$j]->harga_fix;
                    $fix_komponen_material_sum = $fix_komponen_material_sum + $komp_material;
                }
                $arr = [
                    'sub_pekerjaan' => $sub_pekerjaan[$i]->sub_pekerjaan->nama_sub_pekerjaan,
                    'kode_analisa' => $sub_pekerjaan[$i]->sub_pekerjaan->kode_sub_pekerjaan,
                    'volume' => $sub_pekerjaan[$i]->volume,
                    'satuan_sub_pekerjaan' => $sub_pekerjaan[$i]->sub_pekerjaan->satuan_sub_pekerjaan->satuan_sub_pekerjaan,
                    'fix_komponen_jasa_sum' => $fix_komponen_jasa_sum,
                    'fix_komponen_material_sum' => $fix_komponen_material_sum,
                    'fix_komponen' => $fix_komponen_jasa_sum + $fix_komponen_material_sum,
                    'fix_komponen_jasa' => $fix_komponen_jasa,
                    'fix_komponen_material' => $fix_komponen_material,
                ];  
                array_push($komponen, $arr);
            }
            for ($i=0; $i < count($komponen); $i++) { 
                $komponen[$i]['komponen_total'] = $komponen[$i]['fix_komponen_jasa'] + $komponen[$i]['fix_komponen_material'];
            }
            array_push($arr_sub, $komponen);
        }

        for ($i=0; $i < count($pekerjaan); $i++) { 
            $arr_temp = [];
            $total = 0;
            for ($j=0; $j < count($arr_sub[$i]); $j++) { 
                $total = $total + $arr_sub[$i][$j]['komponen_total'];
            }
            $arr_temp = [
                'nama_pekerjaan' => $pekerjaan[$i]->pekerjaan->nama_pekerjaan,
                'total' => $total,
            ];

            array_push($data, $arr_temp);
        }

        $total_all = 0;

        for ($i=0; $i < count($data); $i++) { 
            $total_all = $total_all + $data[$i]['total'];
        }

        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['detail'] = $arr_sub[$i];
        }

        return view('admin.proyek.excel.rab', [
            'datas' => collect($data),
        ], compact('total_all', 'proyek'));
    }

    public function rapPreview($id){
        $proyek = Proyek::find($id);
        $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->where('proyek_id', $id)->get();

        $data = [];

        for ($i=0; $i < count($pekerjaan); $i++) { 
            $arr_pekerjaan = [
                'id' => $pekerjaan[$i]->id,
                'pekerjaan' => $pekerjaan[$i]->pekerjaan->nama_pekerjaan,
                'sub_pekerjaan' => []
            ];

            array_push($data, $arr_pekerjaan);
        }

        for ($i=0; $i < count($data); $i++) {
            $arr_sub_pekerjaan_fix = []; 
            $sub_pekerjaan = ProyekSubPekerjaan::with(['sub_pekerjaan'])->where('proyek_pekerjaan_id', $data[$i]['id'])->get();

            for ($j=0; $j < count($sub_pekerjaan); $j++) { 
                $arr_sub_pekerjaan = [
                    'id' => $sub_pekerjaan[$j]->id,
                    'sub_pekerjaan' => $sub_pekerjaan[$j]->sub_pekerjaan->nama_sub_pekerjaan,
                    'volume' => $sub_pekerjaan[$j]->volume,
                    'komponen_material' => [],
                    'komponen_jasa' => []
                ];

                $komponen_material = ProyekHargaKomponenMaterial::with(['material' => function($query){return $query->with('satuan');}])->where('proyek_sub_pekerjaan_id', $sub_pekerjaan[$j]->id)->get();
                $arr_komponen_material = [];
                for ($k=0; $k < count($komponen_material); $k++) { 
                    $komponen_material_detail = [
                        'nama_material' => $komponen_material[$k]->material->nama_material,
                        'harga_asli' => $komponen_material[$k]->harga_asli,
                        'koefisien' => $komponen_material[$k]->koefisien,
                        'harga_fix' => $komponen_material[$k]->harga_fix,
                        'satuan' => $komponen_material[$k]->material->satuan->satuan_material,
                    ];
                    array_push($arr_komponen_material, $komponen_material_detail);
                }
                $arr_sub_pekerjaan['komponen_material'] = $arr_komponen_material;

                $komponen_jasa = ProyekHargaKomponenJasa::with(['jasa' => function($query){return $query->with('satuan');}])->where('proyek_sub_pekerjaan_id', $sub_pekerjaan[$j]->id)->get();
                $arr_komponen_jasa = [];
                for ($k=0; $k < count($komponen_jasa); $k++) { 
                    $komponen_jasa_detail = [
                        'nama_jasa' => $komponen_jasa[$k]->jasa->nama_jasa,
                        'harga_asli' => $komponen_jasa[$k]->harga_asli,
                        'koefisien' => $komponen_jasa[$k]->koefisien,
                        'harga_fix' => $komponen_jasa[$k]->harga_fix,
                        'satuan' => $komponen_jasa[$k]->jasa->satuan->satuan_jasa,
                    ];
                    array_push($arr_komponen_jasa, $komponen_jasa_detail);
                }
                $arr_sub_pekerjaan['komponen_jasa'] = $arr_komponen_jasa;


                array_push($arr_sub_pekerjaan_fix, $arr_sub_pekerjaan);
            }
            $data[$i]['sub_pekerjaan'] = $arr_sub_pekerjaan_fix;
        }

        $nama_proyek = $proyek->nama_proyek;

        return view('admin.proyek.excel.rap', ['data' => $data], compact('nama_proyek'));
    }
}
