<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RabExport;

class RabController extends Controller
{
    public function index(Request $request, $id){
        return Excel::download(new RabExport($id, $request->tempat, $request->tanggal, $request->nama, $request->npm), 'rab.xlsx');
    }
}
