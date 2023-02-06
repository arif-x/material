<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    public function datatable($id){
        $data = ProyekPekerjaan::with(['pekerjaan'])->where('proyek_id', $id)->orderBy('id', 'desc')->get();
        return datatables()->of($data)->addIndexColumn()->toJson();
    }

    public function index($id){
        $proyek = Proyek::findOrFail($id);
        $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->where('proyek_id', $id)->get();

        $arr_sub = [];
        $arr_fix = [];

        foreach ($pekerjaan as $key => $value) {
            $sub_pekerjaan = ProyekSubPekerjaan::with(['sub_pekerjaan', 'harga_komponen_jasa', 'harga_komponen_material'])->where('proyek_pekerjaan_id', $value['id'])->get();
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

            array_push($arr_fix, $arr_temp);
        }

        $total_all = 0;

        for ($i=0; $i < count($arr_fix); $i++) { 
            $total_all = $total_all + $arr_fix[$i]['total'];
        }
        
        return view('admin.proyek.pekerjaan', compact('proyek', 'arr_fix', 'total_all'));
    }

    public function getSubPekerjaan($id){
        $data = MasterSubPekerjaan::where('pekerjaan_id', $id)->get();
        return response()->json($data);
    }

    public function form($id){
        $proyek = Proyek::findOrFail($id);
        $pekerjaan = MasterPekerjaan::pluck('id', 'nama_pekerjaan');
        return view('admin.proyek.pekerjaan-form', compact('proyek', 'pekerjaan'));
    }

    public function show($id){
        $data = ProyekPekerjaan::with(['pekerjaan'])->find($id);
        return response()->json($data);
    }

    public function store(Request $request){
        $data = [];
        $proyek_id = $request->proyek_id;
        $pekerjaan_id = $request->pekerjaan_id;
        $checkProyekPekerjaan = ProyekPekerjaan::where('proyek_id', $proyek_id)->where('pekerjaan_id', $pekerjaan_id)->first();
        if(empty($checkProyekPekerjaan)){
            $createProyekPekerjaan = ProyekPekerjaan::create(
                [
                    'proyek_id' => $proyek_id,
                    'pekerjaan_id' => $pekerjaan_id,
                ]
            );
            for ($i=0; $i < count($request->checkbox); $i++) { 
                if($request->checkbox[$i] == 1){
                    $data[$i]['proyek_id'] = $proyek_id;
                    $data[$i]['pekerjaan_id'] = $pekerjaan_id;
                    $data[$i]['sub_pekerjaan_id'] = $request->sub_pekerjaan_id[$i];
                    $data[$i]['volume'] = $request->volume[$i];

                    $createProyekSubPekerjaan = ProyekSubPekerjaan::create(
                        [
                            'proyek_pekerjaan_id' => $createProyekPekerjaan->id,
                            'sub_pekerjaan_id' => $data[$i]['sub_pekerjaan_id'],
                            'volume' => $data[$i]['volume'],
                        ]
                    );

                    $this->hargaKomponenJasa($data[$i]['sub_pekerjaan_id'], $createProyekSubPekerjaan->id);
                    $this->hargaKomponenMaterial($data[$i]['sub_pekerjaan_id'], $createProyekSubPekerjaan->id);
                } else {
                    // 
                }
            }
        } else {
            // Kondisine nek wes ono opo?
        }
        return redirect()->route('admin.proyek.pekerjaan-proyek.index', ['id' => $proyek_id]);
    }

    public function storeSingle(Request $request){
        $checkProyekPekerjaan = ProyekSubPekerjaan::where('sub_pekerjaan_id', $request->sub_pekerjaan_id)->where('proyek_pekerjaan_id', $request->pekerjaan_id)->first();
        if(empty($checkProyekPekerjaan)){
            $createProyekSubPekerjaan = ProyekSubPekerjaan::create(
                [
                    'proyek_pekerjaan_id' => $request->pekerjaan_id,
                    'sub_pekerjaan_id' => $request->sub_pekerjaan_id,
                    'volume' => $request->volume,
                ]
            );

            $this->hargaKomponenJasa($request->sub_pekerjaan_id, $createProyekSubPekerjaan->id);
            $this->hargaKomponenMaterial($request->sub_pekerjaan_id, $createProyekSubPekerjaan->id);

            return response()->json('oke');
        } else {
            // Kondisine nek wes ono opo?
            return response()->json('gak oke');
        }
    }

    public function hargaKomponenJasa($id, $new_id){
        $getHargaKomponenJasa = HargaKomponenJasa::with(['jasa'])->where('sub_pekerjaan_id', $id)->get();

        if(count($getHargaKomponenJasa) == 0){
            return 0;
        }

        for ($i=0; $i < count($getHargaKomponenJasa); $i++) { 
            $harga_fix = $getHargaKomponenJasa[$i]->jasa->harga_jasa * $getHargaKomponenJasa[$i]->koefisien;
            $createProyekHargaKomponenJasa = ProyekHargaKomponenJasa::create(
                [
                    'proyek_sub_pekerjaan_id' => $new_id,
                    'jasa_id' => $getHargaKomponenJasa[$i]->jasa->id,
                    'harga_asli' => $getHargaKomponenJasa[$i]->jasa->harga_jasa,
                    'koefisien' => $getHargaKomponenJasa[$i]->koefisien,
                    'harga_fix' => $harga_fix,
                ]
            );
        }
    }

    public function hargaKomponenMaterial($id, $new_id){
        $getHargaKomponenMaterial = HargaKomponenMaterial::with(['material'])->where('sub_pekerjaan_id', $id)->get();

        if(count($getHargaKomponenMaterial) == 0){
            return 0;
        }

        for ($i=0; $i < count($getHargaKomponenMaterial); $i++) { 
            $harga_fix = $getHargaKomponenMaterial[$i]->material->harga_beli * $getHargaKomponenMaterial[$i]->koefisien;
            $createProyekHargaKomponenMaterial = ProyekHargaKomponenMaterial::create(
                [
                    'proyek_sub_pekerjaan_id' => $new_id,
                    'material_id' => $getHargaKomponenMaterial[$i]->material->id,
                    'harga_asli' => $getHargaKomponenMaterial[$i]->material->harga_beli,
                    'koefisien' => $getHargaKomponenMaterial[$i]->koefisien,
                    'harga_fix' => $harga_fix,
                ]
            );
        }
    }

    public function destroy($id){
        $data = ProyekPekerjaan::find($id)->delete();
        return response()->json($data);
    }
}
