<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\ProyekHargaKomponenJasa;
use App\Models\ProyekPekerjaan;
use App\Models\ProyekSubPekerjaan;
use Illuminate\Support\Facades\DB;

class ProyekController extends Controller
{
    public function index(Request $request)
    {
        $data = Proyek::orderBy('id', 'desc')->get();

        foreach ($data as $k => $v) {
            $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->where('proyek_id', $v['id'])->get();

            $arr_sub = [];
            $arr_fix = [];

            foreach ($pekerjaan as $key => $value) {
                $sub_pekerjaan = ProyekSubPekerjaan::with(['sub_pekerjaan', 'harga_komponen_jasa', 'harga_komponen_material'])->where('proyek_pekerjaan_id', $value['id'])->get();
                // return $sub_pekerjaan;
                $komponen = [];
                for ($i = 0; $i < count($sub_pekerjaan); $i++) {
                    $fix_komponen_jasa = 0;
                    $fix_komponen_material = 0;
                    for ($j = 0; $j < count($sub_pekerjaan[$i]->harga_komponen_jasa); $j++) {
                        $komponen_jasa = $sub_pekerjaan[$i]->harga_komponen_jasa[$j]->harga_fix * $sub_pekerjaan[$i]->volume;
                        $fix_komponen_jasa = $fix_komponen_jasa + $komponen_jasa;
                    }
                    for ($j = 0; $j < count($sub_pekerjaan[$i]->harga_komponen_material); $j++) {
                        $komponen_material = $sub_pekerjaan[$i]->harga_komponen_material[$j]->harga_fix * $sub_pekerjaan[$i]->volume;
                        $fix_komponen_material = $fix_komponen_material + $komponen_material;
                    }
                    $arr = [
                        'sub_pekerjaan' => $sub_pekerjaan[$i]->sub_pekerjaan->nama_sub_pekerjaan,
                        'profit' => $sub_pekerjaan[$i]->profit,
                        'fix_komponen_jasa' => $fix_komponen_jasa,
                        'fix_komponen_material' => $fix_komponen_material,
                    ];
                    array_push($komponen, $arr);
                }
                for ($i = 0; $i < count($komponen); $i++) {
                    $komponen[$i]['komponen_total'] = $komponen[$i]['fix_komponen_jasa'] + $komponen[$i]['fix_komponen_material'];
                    $komponen[$i]['komponen_total_profit'] = ($komponen[$i]['fix_komponen_jasa'] + $komponen[$i]['fix_komponen_jasa'] * $komponen[$i]['profit'] / 100) + ($komponen[$i]['fix_komponen_material'] + $komponen[$i]['fix_komponen_material'] * $komponen[$i]['profit'] / 100);
                }
                array_push($arr_sub, $komponen);
            }

            for ($i = 0; $i < count($pekerjaan); $i++) {
                $arr_temp = [];
                $total = 0;
                $total_profit = 0;
                for ($j = 0; $j < count($arr_sub[$i]); $j++) {
                    $total = $total + $arr_sub[$i][$j]['komponen_total'];
                    $total_profit = $total_profit + $arr_sub[$i][$j]['komponen_total_profit'];
                }
                $arr_temp = [
                    'nama_pekerjaan' => $pekerjaan[$i]->pekerjaan->nama_pekerjaan,
                    'total' => $total,
                    'total_profit' => $total_profit,
                ];

                array_push($arr_fix, $arr_temp);
            }

            $total_all = 0;
            $total_all_profit = 0;

            for ($i = 0; $i < count($arr_fix); $i++) {
                $total_all = $total_all + $arr_fix[$i]['total'];
                $total_all_profit = $total_all_profit + $arr_fix[$i]['total_profit'];
            }
            $data[$k]['total'] = $total_all;
            $data[$k]['total_profit'] = $total_all_profit;
        }

        if ($request->ajax()) {
            return datatables()->of($data)->addIndexColumn()
                ->toJson();
        }

        return view('admin.proyek.proyek');
    }

    public function show($id)
    {
        $data = Proyek::find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = Proyek::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'nama_proyek' => $request->nama_proyek
            ]
        );

        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = Proyek::find($id)->delete();
        return response()->json($data);
    }

    public function sync_harga($id)
    {
        DB::beginTransaction();
        try {
            $proyek_pekerjaan = ProyekPekerjaan::with(['sub_pekerjaan.harga_komponen_jasa.jasa', 'sub_pekerjaan.harga_komponen_material.material'])->where('proyek_id', $id)->get();
            $sub_pekerjaan_proyek_jasa = [];
            $sub_pekerjaan_proyek_material = [];
            for ($i = 0; $i < count($proyek_pekerjaan); $i++) {
                $sub_pekerjaan_proyek_jasa_idx = [];
                $sub_pekerjaan_proyek_material_idx = [];
                for ($j = 0; $j < count($proyek_pekerjaan[$i]['sub_pekerjaan']); $j++) {
                    for ($k = 0; $k < count($proyek_pekerjaan[$i]['sub_pekerjaan'][$j]['harga_komponen_jasa']); $k++) {
                        array_push($sub_pekerjaan_proyek_jasa_idx, $proyek_pekerjaan[$i]['sub_pekerjaan'][$j]['harga_komponen_jasa'][$k]);
                    }
                    for ($k = 0; $k < count($proyek_pekerjaan[$i]['sub_pekerjaan'][$j]['harga_komponen_material']); $k++) {
                        array_push($sub_pekerjaan_proyek_material_idx, $proyek_pekerjaan[$i]['sub_pekerjaan'][$j]['harga_komponen_material'][$k]);
                    }
                }
                array_push($sub_pekerjaan_proyek_jasa, $sub_pekerjaan_proyek_jasa_idx);
                array_push($sub_pekerjaan_proyek_material, $sub_pekerjaan_proyek_material_idx);
            }

            for ($i = 0; $i < count($sub_pekerjaan_proyek_jasa); $i++) {
                for ($j=0; $j < count($sub_pekerjaan_proyek_jasa[$i]); $j++) { 
                    ProyekHargaKomponenJasa::where('id', $sub_pekerjaan_proyek_jasa[$i][$j]['id'])
                    ->update([
                        'harga_asli' => $sub_pekerjaan_proyek_jasa[$i][$j]['jasa']['harga_jasa'],
                        'harga_fix' => $sub_pekerjaan_proyek_jasa[$i][$j]['jasa']['harga_jasa'] * $sub_pekerjaan_proyek_jasa[$i][$j]['koefisien'],
                    ]);
                }  
            }

            for ($i = 0; $i < count($sub_pekerjaan_proyek_material); $i++) {
                for ($j=0; $j < count($sub_pekerjaan_proyek_material[$i]); $j++) { 
                    ProyekHargaKomponenJasa::where('id', $sub_pekerjaan_proyek_material[$i][$j]['id'])
                    ->update([
                        'harga_asli' => $sub_pekerjaan_proyek_material[$i][$j]['material']['harga_beli'],
                        'harga_fix' => $sub_pekerjaan_proyek_material[$i][$j]['material']['harga_beli'] * $sub_pekerjaan_proyek_material[$i][$j]['koefisien'],
                    ]);
                }  
            }
            DB::commit();
            return response()->json("OK");
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }
}
