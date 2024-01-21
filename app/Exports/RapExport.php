<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use App\Models\Proyek;
use App\Models\ProyekPekerjaan;
use App\Models\ProyekSubPekerjaan;
use App\Models\ProyekHargaKomponenMaterial;
use App\Models\ProyekHargaKomponenJasa;

class RapExport implements FromView, ShouldAutoSize
{
    private $id;
    
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function view(): View {
        $proyek = Proyek::find($this->id);
        $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->where('proyek_id', $this->id)->get();

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
