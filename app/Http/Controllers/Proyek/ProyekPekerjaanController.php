<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Proyek;
use App\Models\ProyekPekerjaan;
use App\Models\ProyekSubPekerjaan;
use App\Models\ProyekHargaKomponenJasa;
use App\Models\ProyekHargaKomponenMaterial;
use App\Models\MasterPekerjaan;
use App\Models\MasterSubPekerjaan;
use App\Models\HargaKomponenJasa;
use App\Models\HargaKomponenMaterial;

class ProyekPekerjaanController extends Controller
{
    public function datatable($id)
    {
        $data = ProyekPekerjaan::with(['pekerjaan'])->where('proyek_id', $id)->orderBy('id', 'desc')->get();

        foreach ($data as $key => $value) {
            $arr_sub = [];
            $arr_fix = [];
            $sub_pekerjaan = ProyekSubPekerjaan::with(['sub_pekerjaan', 'harga_komponen_jasa', 'harga_komponen_material'])->where('proyek_pekerjaan_id', $value['id'])->get();
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

            $total_all = 0;
            $total_all_profit = 0;
            for ($i = 0; $i < count($komponen); $i++) {
                $total_all = $komponen[$i]['komponen_total'] + $total_all;
                $total_all_profit = $komponen[$i]['komponen_total_profit'] + $total_all_profit;
            }

            $data[$key]['total'] = $total_all;
            $data[$key]['total_profit'] = $total_all_profit;
        }

        return datatables()->of($data)->addIndexColumn()
            ->toJson();
    }

    public function index($id)
    {
        $proyek = Proyek::findOrFail($id);
        $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->where('proyek_id', $id)->orderBy('id', 'desc')->get();

        $arr_sub = [];
        $arr_fix = [];

        foreach ($pekerjaan as $key => $value) {
            $sub_pekerjaan = ProyekSubPekerjaan::with(['sub_pekerjaan', 'harga_komponen_jasa', 'harga_komponen_material'])->where('proyek_pekerjaan_id', $value['id'])->get();
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

        return view('admin.proyek.pekerjaan', compact('proyek', 'arr_fix', 'total_all', 'total_all_profit'));
    }

    public function getSubPekerjaan($proyek_id, $pekerjaan_id)
    {
        $pekerjaan = ProyekPekerjaan::where('proyek_id', $proyek_id)->where('pekerjaan_id', $pekerjaan_id)->first();
        $sub_pekerjaan_proyek_has = [];
        if($pekerjaan) {
            $sub_pekerjaan_proyek_has = ProyekSubPekerjaan::where('proyek_pekerjaan_id', $pekerjaan->id)->pluck('sub_pekerjaan_id');
        }
        $data = MasterSubPekerjaan::where('pekerjaan_id', $pekerjaan_id)->whereNotIn('id', $sub_pekerjaan_proyek_has)->get();
        return response()->json($data);
    }

    public function form($id)
    {
        $proyek = Proyek::findOrFail($id);
        $pekerjaan = MasterPekerjaan::pluck('id', 'nama_pekerjaan');
        return view('admin.proyek.pekerjaan-form', compact('proyek', 'pekerjaan'));
    }

    public function show($id)
    {
        $data = ProyekPekerjaan::with(['pekerjaan'])->find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = [];
            $proyek_id = $request->proyek_id;
            $pekerjaan_id = $request->pekerjaan_id;
            $checkProyekPekerjaan = ProyekPekerjaan::where('proyek_id', $proyek_id)->where('pekerjaan_id', $pekerjaan_id)->first();
            if (empty($checkProyekPekerjaan)) {
                $createProyekPekerjaan = ProyekPekerjaan::create(
                    [
                        'proyek_id' => $proyek_id,
                        'pekerjaan_id' => $pekerjaan_id,
                    ]
                );
                for ($i = 0; $i < count($request->checkbox); $i++) {
                    if ($request->checkbox[$i] == 1) {
                        $data[$i]['proyek_id'] = $proyek_id;
                        $data[$i]['pekerjaan_id'] = $pekerjaan_id;
                        $data[$i]['sub_pekerjaan_id'] = $request->sub_pekerjaan_id[$i];
                        $data[$i]['volume'] = $request->volume[$i] ?? 0;
                        $data[$i]['profit'] = $request->profit[$i] ?? 0;

                        $createProyekSubPekerjaan = ProyekSubPekerjaan::create(
                            [
                                'proyek_pekerjaan_id' => $createProyekPekerjaan->id,
                                'sub_pekerjaan_id' => $data[$i]['sub_pekerjaan_id'],
                                'volume' => $data[$i]['volume'],
                                'profit' => $data[$i]['profit'],
                            ]
                        );

                        $this->hargaKomponenJasa($data[$i]['sub_pekerjaan_id'], $createProyekSubPekerjaan->id);
                        $this->hargaKomponenMaterial($data[$i]['sub_pekerjaan_id'], $createProyekSubPekerjaan->id);
                    } else {
                        // 
                    }
                }
            } else {
                // Nek wes ono kondisine opo?
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        return redirect()->route('admin.proyek.pekerjaan-proyek.index', ['id' => $proyek_id]);
    }

    public function storeSingle(Request $request)
    {
        $checkProyekPekerjaan = ProyekSubPekerjaan::where('sub_pekerjaan_id', $request->sub_pekerjaan_id)->where('proyek_pekerjaan_id', $request->pekerjaan_id)->first();
        if (empty($checkProyekPekerjaan)) {
            $createProyekSubPekerjaan = ProyekSubPekerjaan::create(
                [
                    'proyek_pekerjaan_id' => $request->pekerjaan_id,
                    'sub_pekerjaan_id' => $request->sub_pekerjaan_id,
                    'volume' => $request->volume,
                    'profit' => MasterSubPekerjaan::where('id', $request->sub_pekerjaan_id)->value('profit'),
                ]
            );

            $this->hargaKomponenJasa($request->sub_pekerjaan_id, $createProyekSubPekerjaan->id);
            $this->hargaKomponenMaterial($request->sub_pekerjaan_id, $createProyekSubPekerjaan->id);

            return response()->json([
                'status' => true,
                'message' => 'Sukses',
                'data' => $createProyekSubPekerjaan
            ], 200);
        } else {
            // Kondisine nek wes ono opo?
            return response()->json([
                'status' => false,
                'message' => 'sub pekerjaan has already taken',
                'data' => null
            ], 500);
        }
    }

    public function hargaKomponenJasa($id, $new_id)
    {
        $getHargaKomponenJasa = HargaKomponenJasa::with(['jasa'])->where('sub_pekerjaan_id', $id)->get();

        if (count($getHargaKomponenJasa) == 0) {
            return 0;
        }

        for ($i = 0; $i < count($getHargaKomponenJasa); $i++) {
            $harga_fix = $getHargaKomponenJasa[$i]->jasa->harga_jasa * $getHargaKomponenJasa[$i]->koefisien;
            $createProyekHargaKomponenJasa = ProyekHargaKomponenJasa::create(
                [
                    'proyek_sub_pekerjaan_id' => $new_id,
                    'jasa_id' => $getHargaKomponenJasa[$i]->jasa->id,
                    'harga_asli' => $getHargaKomponenJasa[$i]->jasa->harga_jasa,
                    'koefisien' => $getHargaKomponenJasa[$i]->koefisien,
                    // 'profit' => $getHargaKomponenJasa[$i]->profit,
                    'profit' => 0,
                    'harga_fix' => $harga_fix,
                ]
            );
        }
    }

    public function hargaKomponenMaterial($id, $new_id)
    {
        $getHargaKomponenMaterial = HargaKomponenMaterial::with(['material'])->where('sub_pekerjaan_id', $id)->get();

        if (count($getHargaKomponenMaterial) == 0) {
            return 0;
        }

        for ($i = 0; $i < count($getHargaKomponenMaterial); $i++) {
            $harga_fix = $getHargaKomponenMaterial[$i]->material->harga_beli * $getHargaKomponenMaterial[$i]->koefisien;
            $createProyekHargaKomponenMaterial = ProyekHargaKomponenMaterial::create(
                [
                    'proyek_sub_pekerjaan_id' => $new_id,
                    'material_id' => $getHargaKomponenMaterial[$i]->material->id,
                    'harga_asli' => $getHargaKomponenMaterial[$i]->material->harga_beli,
                    'koefisien' => $getHargaKomponenMaterial[$i]->koefisien,
                    // 'profit' => $getHargaKomponenMaterial[$i]->profit,
                    'profit' => 0,
                    'harga_fix' => $harga_fix,
                ]
            );
        }
    }

    public function destroy($id)
    {
        $data = ProyekPekerjaan::find($id)->delete();
        return response()->json($data);
    }
}
