<?php

namespace App\Http\Controllers\Master\Pekerjaan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\SatuanSubPekerjaan;
use App\Models\MasterSubPekerjaan;
use App\Models\MasterPekerjaan;
use App\Models\HargaKomponenJasa;
use App\Models\HargaKomponenMaterial;

class SubPekerjaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterSubPekerjaan::with('pekerjaan')->orderBy('id', 'desc')->get();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['komponen_jasa'] = HargaKomponenJasa::where('sub_pekerjaan_id', $data[$i]['id'])->sum('harga_komponen_jasa');
                $data[$i]['komponen_material'] = HargaKomponenMaterial::where('sub_pekerjaan_id', $data[$i]['id'])->sum('harga_komponen_material');
                $data[$i]['total_komponen'] = $data[$i]['komponen_jasa'] + $data[$i]['komponen_material'];
            }
            return datatables()->of($data)->addIndexColumn()->toJson();
        }

        $pekerjaan = MasterPekerjaan::pluck('nama_pekerjaan', 'id');
        $satuan_sub_pekerjaan = SatuanSubPekerjaan::pluck('satuan_sub_pekerjaan', 'id');
        return view('admin.master.pekerjaan.sub-pekerjaan', compact('pekerjaan', 'satuan_sub_pekerjaan'));
    }

    public function single(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = MasterSubPekerjaan::with(['pekerjaan', 'satuan_sub_pekerjaan'])->where('pekerjaan_id', $id)->orderBy('id', 'desc')->get();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['komponen_jasa'] = HargaKomponenJasa::where('sub_pekerjaan_id', $data[$i]['id'])->sum('harga_komponen_jasa');
                $data[$i]['komponen_material'] = HargaKomponenMaterial::where('sub_pekerjaan_id', $data[$i]['id'])->sum('harga_komponen_material');
                $data[$i]['total_komponen'] = $data[$i]['komponen_jasa'] + $data[$i]['komponen_material'];
            }
            return datatables()->of($data)->addIndexColumn()->toJson();
        }

        $nama_pekerjaan = MasterPekerjaan::where('id', $id)->value('nama_pekerjaan');
        $pekerjaan = MasterPekerjaan::pluck('nama_pekerjaan', 'id');
        $satuan_sub_pekerjaan = SatuanSubPekerjaan::pluck('satuan_sub_pekerjaan', 'id');

        return view('admin.master.pekerjaan.sub-pekerjaan-single', compact('id', 'nama_pekerjaan', 'satuan_sub_pekerjaan', 'pekerjaan'));
    }

    public function show($id)
    {
        $data = MasterSubPekerjaan::find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            if ($request->id) {
                $validation = Validator::make($request->all(), [
                    'pekerjaan_id' => ['required'],
                    'satuan_sub_pekerjaan_id' => ['required'],
                    'kode_sub_pekerjaan' => [
                        'required', 
                        Rule::unique('master_sub_pekerjaans')->where(function ($query) use ($request) {
                            return $query->where('pekerjaan_id', $request->pekerjaan_id);
                        })->ignore($request->id)
                    ],
                    'profit' => ['required'],
                    'nama_sub_pekerjaan' => ['required']
                ]);
            
                if ($validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation errors',
                        'data' => $validation->errors()
                    ], 422);
                }
            } else {
                $validation = Validator::make($request->all(), [
                    'pekerjaan_id' => ['required'],
                    'satuan_sub_pekerjaan_id' => ['required'],
                    'kode_sub_pekerjaan' => [
                        'required', 
                        Rule::unique('master_sub_pekerjaans')->where(function ($query) use ($request) {
                            return $query->where('pekerjaan_id', $request->pekerjaan_id);
                        })
                    ],
                    'profit' => ['required'],
                    'nama_sub_pekerjaan' => ['required']
                ]);
            
                if ($validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation errors',
                        'data' => $validation->errors()
                    ], 422);
                }
            }            

            $data = MasterSubPekerjaan::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'pekerjaan_id' => $request->pekerjaan_id,
                    'satuan_sub_pekerjaan_id' => $request->satuan_sub_pekerjaan_id,
                    'kode_sub_pekerjaan' => $request->kode_sub_pekerjaan,
                    'profit' => $request->profit,
                    'nama_sub_pekerjaan' => $request->nama_sub_pekerjaan,
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Sukses',
                'data' => $data
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        $data = MasterSubPekerjaan::find($id)->delete();
        return response()->json($data);
    }

    public function copy(Request $request)
    {
        $sub_pekerjaan = MasterSubPekerjaan::create(
            [
                'pekerjaan_id' => $request->pekerjaan_id,
                'satuan_sub_pekerjaan_id' => $request->satuan_sub_pekerjaan_id,
                'kode_sub_pekerjaan' => $request->kode_sub_pekerjaan,
                'profit' => $request->profit,
                'nama_sub_pekerjaan' => $request->nama_sub_pekerjaan,
            ]
        );

        $sub_pekerjaan_jasa = [];
        $sub_pekerjaan_material = [];

        $komponen_jasa_arr = HargaKomponenJasa::where('sub_pekerjaan_id', $request->sub_pekerjaan_id)->get();
        for ($i = 0; $i < count($komponen_jasa_arr); $i++) {
            $komponen_jasa = HargaKomponenJasa::create(
                [
                    'jasa_id' => $komponen_jasa_arr[$i]['jasa_id'],
                    'sub_pekerjaan_id' => $sub_pekerjaan->id,
                    'koefisien' => $komponen_jasa_arr[$i]['koefisien'],
                    'profit' => 0,
                    'harga_komponen_jasa' => $komponen_jasa_arr[$i]['harga_komponen_jasa']
                ]
            );

            array_push($sub_pekerjaan_jasa, $komponen_jasa);
        }

        $komponen_material_arr = HargaKomponenMaterial::where('sub_pekerjaan_id', $request->sub_pekerjaan_id)->get();
        for ($i = 0; $i < count($komponen_material_arr); $i++) {
            $komponen_material = HargaKomponenMaterial::create(
                [
                    'material_id' => $komponen_material_arr[$i]['material_id'],
                    'sub_pekerjaan_id' => $sub_pekerjaan->id,
                    'koefisien' => $komponen_material_arr[$i]['koefisien'],
                    'profit' => 0,
                    'harga_komponen_material' => $komponen_material_arr[$i]['harga_komponen_material']
                ]
            );

            array_push($sub_pekerjaan_material, $komponen_material);
        }

        $sub_pekerjaan['jasa'] = $sub_pekerjaan_jasa;
        $sub_pekerjaan['material'] = $sub_pekerjaan_material;

        return $sub_pekerjaan;
    }
}
