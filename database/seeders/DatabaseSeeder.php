<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                // User
                UserSeeder::class,

                // Material
                JenisMaterialSeeder::class,
                SatuanMaterialSeeder::class,
                MaterialSeeder::class,
                
                // Pekerjaan & Sub Pekerjaan
                PekerjaanSeeder::class,
                SubPekerjaanSeeder::class,

                // Jasa
                JenisJasaSeeder::class,
                SatuanJasaSeeder::class,
                JasaSeeder::class,

                // Harga Komponen
                // HargaKomponenJasaSeeder::class,
                // HargaKomponenMaterialSeeder::class,
            ]
        );
    }
}
