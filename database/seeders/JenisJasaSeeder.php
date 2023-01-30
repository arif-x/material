<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisJasa;

class JenisJasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 5; $i++){
            JenisJasa::insert([
                'jenis_jasa' => "Jenis Jasa ".($i+1),
            ]);
        }
    }
}
