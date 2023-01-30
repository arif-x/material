<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 5; $i++) { 
            User::create(
                [
                    'name' => 'Admin '.($i+1),
                    'email' => 'admin'.($i+1).'@gmail.com',
                    'password' =>  Hash::make('password'),
                ]
            );
        }
    }
}
