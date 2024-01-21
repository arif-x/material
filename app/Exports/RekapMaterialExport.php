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
use App\Models\SatuanMaterial;

class RekapMaterialExport implements FromView, ShouldAutoSize
{
    private $id;
    
    public function __construct(int $id)
    {
        $this->id = $id;
    }


    public function view(): View {
        $proyek = Proyek::find($this->id);
        $pekerjaan = ProyekPekerjaan::with(
            [
                'sub_pekerjaan' => function($query){
                    return $query->with(
                        [
                            'harga_komponen_material' => function($query){
                                return $query->with(['material']);
                            }
                        ]
                    );
                }
            ]
        )->where('proyek_id', $this->id)->get();

        $data_material = [];

        for ($i=0; $i < count($pekerjaan); $i++) { 
            for ($j=0; $j < count($pekerjaan[$i]->sub_pekerjaan); $j++) { 
                for ($k=0; $k < count($pekerjaan[$i]->sub_pekerjaan[$j]->harga_komponen_material); $k++) { 
                    if(empty($pekerjaan[$i]->sub_pekerjaan[$j]->harga_komponen_material)){
                        // 
                    } else {
                        $pekerjaan[$i]->sub_pekerjaan[$j]->harga_komponen_material[$k]['volume'] = $pekerjaan[$i]->sub_pekerjaan[$j]['volume'];
                        array_push($data_material, $pekerjaan[$i]->sub_pekerjaan[$j]->harga_komponen_material[$k]);
                    }
                }
            }
        }

        $nama_material = [];

        foreach ($data_material as $data) {
            array_push($nama_material, $data->material->nama_material);
        }

        $nama_material = array_unique($nama_material);
        $nama_material_f = [];

        foreach ($nama_material as $nama) {
            $namas['nama_material'] = $nama;
            array_push($nama_material_f, $namas);
        }

        for ($i=0; $i < count($nama_material_f); $i++) { 
            $jumlah = 0;
            $harga_satuan = 0;
            $count_per = 0;
            $volume = 0;
            $satuan = 0;
            $kode = '';
            foreach ($data_material as $data) {
                if($data->material->nama_material == $nama_material_f[$i]['nama_material']){
                    $jumlah = $jumlah + ($data->koefisien * $data->volume);
                    $harga_satuan = $harga_satuan + ($data->volume * $data->harga_asli * $data->koefisien);
                    $volume = $volume + $data->volume;
                    $satuan = $data->material->satuan_material_id;
                    $count_per = $count_per + 1;
                    $kode = $data->material->kode_material;
                }
            }
            $nama_material_f[$i]['kode'] = $kode;
            $nama_material_f[$i]['jumlah'] = $jumlah;
            $nama_material_f[$i]['satuan'] = $satuan;
            $nama_material_f[$i]['harga_satuan'] = ($harga_satuan/$jumlah);
        }

        for ($i=0; $i < count($nama_material_f); $i++) { 
            $nama_material_f[$i]['nama_satuan'] = SatuanMaterial::where('id', $nama_material_f[$i]['satuan'])->value('satuan_material');   
        }

        $nama_proyek = $proyek->nama_proyek;

        return view('admin.proyek.excel.rekap-material', ['data' => $nama_material_f], compact('nama_proyek'));
    
    }
}
