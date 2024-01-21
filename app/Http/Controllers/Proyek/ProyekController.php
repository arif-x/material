<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\ProyekPekerjaan;
use App\Models\ProyekSubPekerjaan;

class ProyekController extends Controller
{
    public function index(Request $request)
    {
        $data = Proyek::orderBy('id', 'desc')->get();

        foreach($data as $k => $v){
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
}
