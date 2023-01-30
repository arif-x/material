<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\MasterJasa;
use App\Models\MasterSubPekerjaan;
use App\Models\HargaKomponenJasa;

class HargaKomponenJasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $mj = MasterJasa::all();
        $msp = MasterSubPekerjaan::all();

        $koefisien_arr = [
            0.1,
            0.2,
            0.3,
            0.4,
            0.5,
        ];

        for($i = 0; $i < 15; $i++){
            $hjId = $mj->random()->id;
            $koefisien_rand = array_rand($koefisien_arr);
            $koefisien = $koefisien_arr[$koefisien_rand];
            $harga_jasa = MasterJasa::where('id', $hjId)->value('harga_jasa');

            HargaKomponenJasa::insert([
                'jasa_id' => $hjId,
                'sub_pekerjaan_id' => $msp->random()->id,
                'koefisien' => $koefisien,
                'harga_komponen_jasa' => $koefisien * $harga_jasa
            ]);
        }
    }
}
