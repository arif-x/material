<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SatuanMaterial;

class SatuanMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 5; $i++){
            SatuanMaterial::insert([
                'satuan_material' => "M".($i+1),
            ]);
        }
    }
}
