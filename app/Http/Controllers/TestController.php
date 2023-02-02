<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterSubPekerjaan;

class TestController extends Controller
{
    public function index(){
        $data = MasterSubPekerjaan::with(['harga_satuan_jasa', 'harga_satuan_material'])->find(5);
        return response()->json($data);
    }
}
