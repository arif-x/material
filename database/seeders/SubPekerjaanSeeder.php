<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\MasterPekerjaan;
use App\Models\MasterSubPekerjaan;

class SubPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        
        $mp = MasterPekerjaan::all();

        for($i = 0; $i < 5; $i++){
            MasterSubPekerjaan::insert([
                'pekerjaan_id' => $mp->random()->id,
                'kode_sub_pekerjaan' => "KSP-" . $faker->numberBetween(10000,99999),
                'nama_sub_pekerjaan' => "Sub Pekerjaan ".($i+1),
            ]);
        }
    }
}
