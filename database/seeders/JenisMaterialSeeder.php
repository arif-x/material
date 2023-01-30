<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisMaterial;

class JenisMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 5; $i++){
            JenisMaterial::insert([
                'jenis_material' => "Jenis Material ".($i+1),
            ]);
        }
    }
}
