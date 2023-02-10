<?php

namespace App\Http\Controllers\Proyek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RabExport;
use App\Exports\RapExport;

class RabRapController extends Controller
{
    public function rab(Request $request, $id){
        // return Excel::download(new RabExport($id, $request->tempat, $request->tanggal, $request->nama, $request->npm), 'rab.xlsx');
        return Excel::download(new RabExport($id), 'rab.xlsx');
    }

    public function rap(Request $request, $id){
        return Excel::download(new RapExport($id), 'rap.xlsx');
    }
}
