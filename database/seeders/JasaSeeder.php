<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\SatuanJasa;
use App\Models\JenisJasa;
use App\Models\MasterJasa;

class JasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $jj = JenisJasa::all();
        $sj = SatuanJasa::all();

        $harga = [
            10000,
            20000,
            30000,
            40000,
            50000,
        ];

        for($i = 0; $i < 5; $i++){
            $rand_harga = array_rand($harga);
            MasterJasa::insert([
                'jenis_jasa_id' => $jj->random()->id,
                'satuan_jasa_id' => $sj->random()->id,
                'kode_jasa' => 'KJ '.$faker->numberBetween(10000,99999),
                'nama_jasa' => 'Jasa '.($i+1),
                'harga_jasa' => $harga[$rand_harga],
            ]);
        }
    }
}
