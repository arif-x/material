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

class RabExport implements FromView, ShouldAutoSize
{
    public function __construct(int $id, $tempat, $tanggal, $nama, $npm)
    {
        $this->id = $id;
        $this->tempat = $tempat;
        $this->tanggal = $tanggal;
        $this->nama = $nama;
        $this->npm = $npm;
    }

    public function view(): View {
        $proyek = Proyek::find($this->id);
        $pekerjaan = ProyekPekerjaan::with(['pekerjaan'])->where('proyek_id', $this->id)->get();

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
            'tempat' => $this->tempat,
            'tanggal' => $this->tanggal,
            'nama' => $this->nama,
            'npm' => $this->npm
        ], compact('total_all', 'proyek'));
    }
}
