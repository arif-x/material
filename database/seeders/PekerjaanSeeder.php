<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\MasterPekerjaan;

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        
        for($i = 0; $i < 5; $i++){
            MasterPekerjaan::insert([
                'kode_pekerjaan' => "KP-" . $faker->numberBetween(10000,99999),
                'nama_pekerjaan' => "Pekerjaan ".($i+1),
            ]);
        }
    }
}
