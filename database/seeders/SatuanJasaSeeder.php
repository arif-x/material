<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SatuanJasa;

class SatuanJasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 5; $i++){
            SatuanJasa::insert([
                'satuan_jasa' => "J".($i+1),
            ]);
        }
    }
}
