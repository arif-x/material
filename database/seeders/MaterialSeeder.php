<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\MasterMaterial;
use App\Models\JenisMaterial;
use App\Models\SatuanMaterial;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        
        $jm = JenisMaterial::all();
        $sm = SatuanMaterial::all();

        $harga = [
            10000,
            20000,
            30000,
            40000,
            50000,
        ];

        for($i = 0; $i < 5; $i++){
            $rand_harga = array_rand($harga);
            MasterMaterial::insert([
                'kode_material' => "KM-" . $faker->numberBetween(10000,99999),
                'nama_material' => 'Material '.($i+1),
                'jenis_material_id' => $jm->random()->id,
                'satuan_material_id' => $sm->random()->id,
                'harga_beli' => $harga[$rand_harga],
            ]);
        }
    }
}
