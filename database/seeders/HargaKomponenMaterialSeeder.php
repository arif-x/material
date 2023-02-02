<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\MasterMaterial;
use App\Models\MasterSubPekerjaan;
use App\Models\HargaKomponenMaterial;

class HargaKomponenMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $mm = MasterMaterial::all();
        $msp = MasterSubPekerjaan::all();

        $koefisien_arr = [
            0.1,
            0.2,
            0.3,
            0.4,
            0.5,
        ];

        for($i = 0; $i < 25; $i++){
            $hmId = $mm->random()->id;
            $koefisien_rand = array_rand($koefisien_arr);
            $koefisien = $koefisien_arr[$koefisien_rand];
            $harga_beli = MasterMaterial::where('id', $hmId)->value('harga_beli');
            HargaKomponenMaterial::insert([
                'material_id' => $hmId,
                'sub_pekerjaan_id' => $msp->random()->id,
                'koefisien' => $koefisien,
                'harga_komponen_material' => $koefisien * $harga_beli
            ]);
        }
    }
}
