<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(){
        $harga = [
            10000,
            20000,
            30000,
            40000,
            50000,
        ];
        $k = array_rand($harga);
        $v = $harga[$k];
        echo $v;
    }
}
