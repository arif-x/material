<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SatuanSubPekerjaan;

class SatuanSubPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 5; $i++) { 
            SatuanSubPekerjaan::insert(
                [
                    'satuan_sub_pekerjaan' => 'S'.($i+1)
                ]
            );
        }
    }
}
