<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;

use App\Http\Controllers\Data\GetHargaController;
use App\Http\Controllers\Master\Jasa\JenisJasaController;
use App\Http\Controllers\Master\Jasa\SatuanJasaController;
use App\Http\Controllers\Master\Jasa\JasaController;

use App\Http\Controllers\Master\Material\JenisMaterialController;
use App\Http\Controllers\Master\Material\SatuanMaterialController;
use App\Http\Controllers\Master\Material\MaterialController;

use App\Http\Controllers\Master\Pekerjaan\PekerjaanController;
use App\Http\Controllers\Master\Pekerjaan\SubPekerjaanController;
use App\Http\Controllers\Master\Pekerjaan\SubPekerjaanDetailController;

use App\Http\Controllers\Master\HargaKomponen\HargaKomponenJasaController;
use App\Http\Controllers\Master\HargaKomponen\HargaKomponenMaterialController;

use App\Http\Controllers\Proyek\ProyekController;
use App\Http\Controllers\Proyek\ProyekPekerjaanController;
use App\Http\Controllers\Proyek\ProyekPekerjaanDetailController;
use App\Http\Controllers\Proyek\ProyekSubPekerjaanDetailController;
use App\Http\Controllers\Proyek\RabController;

use App\Http\Controllers\Proyek\HargaKomponen\HargaKomponenJasaController as ProyekHargaKomponenJasaController;
use App\Http\Controllers\Proyek\HargaKomponen\HargaKomponenMaterialController as ProyekHargaKomponenMaterialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/test', [App\Http\Controllers\TestController::class, 'index'])->name('test');

// Master Route
Route::group([
    'prefix' => 'master',
    'middleware' => 'auth'
], function(){
    // Data
    Route::group([
        'prefix' => 'data',
        'as' => 'admin.data.'
    ], function(){
        Route::get('/harga-material/{id}', [GetHargaController::class, 'material'])->name('get-harga-material');  
        Route::get('/harga-jasa/{id}', [GetHargaController::class, 'jasa'])->name('get-harga-jasa');
    });
    // Material
    Route::group([
        'prefix' => 'material',
        'as' => 'admin.master.material.'
    ], function(){
        Route::resource('/jenis-material', JenisMaterialController::class);  
        Route::resource('/satuan-material', SatuanMaterialController::class);   
        Route::resource('/material', MaterialController::class);  
    });
    // Pekerjaan
    Route::group([
        'prefix' => 'pekerjaan',
        'as' => 'admin.master.pekerjaan.'
    ], function(){
        Route::resource('/pekerjaan', PekerjaanController::class);
        Route::get('/detail/sub-pekerjaan/{id}', [SubPekerjaanController::class, 'single'])->name('sub-pekerjaan.single');
        Route::resource('/sub-pekerjaan', SubPekerjaanController::class);
        Route::get('/sub-pekerjaan/detail/{id}', [SubPekerjaanDetailController::class, 'index'])->name('sub-pekerjaan.detail');
        Route::get('/sub-pekerjaan/detail/jasa/{id}', [SubPekerjaanDetailController::class, 'jasaDatatable'])->name('sub-pekerjaan.detail.jasa');
        Route::get('/sub-pekerjaan/detail/material/{id}', [SubPekerjaanDetailController::class, 'materialDatatable'])->name('sub-pekerjaan.detail.material');
    });
    // Jasa
    Route::group([
        'prefix' => 'jasa',
        'as' => 'admin.master.jasa.'
    ], function(){
        Route::resource('/jenis-jasa', JenisJasaController::class);  
        Route::resource('/satuan-jasa', SatuanJasaController::class);   
        Route::resource('/jasa', JasaController::class);  
    });
    // Harga Komponen
    Route::group([
        'prefix' => 'harga-komponen',
        'as' => 'admin.master.harga-komponen.'
    ], function(){
        Route::resource('/harga-komponen-jasa', HargaKomponenJasaController::class);
        Route::resource('/harga-komponen-material', HargaKomponenMaterialController::class);
    });
});

// Proyek Route
Route::group([
    'prefix' => 'proyek',
    'middleware' => 'auth',
    'as' => 'admin.proyek.'
], function(){
    // Proyek
    Route::resource('/proyek', ProyekController::class);
    // Pekerjaan Proyek
    Route::group([
        'prefix' => 'pekerjaan-proyek'
    ],  function(){
        Route::get('/datatable/{id}', [ProyekPekerjaanController::class, 'datatable'])->name('pekerjaan-proyek.datatable');
        Route::get('/{id}', [ProyekPekerjaanController::class, 'index'])->name('pekerjaan-proyek.index');
        Route::get('/form/{id}', [ProyekPekerjaanController::class, 'form'])->name('pekerjaan-proyek.form');
        Route::get('/get-sub-pekerjaan/{id}', [ProyekPekerjaanController::class, 'getSubPekerjaan'])->name('pekerjaan-proyek.get-sub-pekerjaan');
        Route::get('/show/{id}', [ProyekPekerjaanController::class, 'show'])->name('pekerjaan-proyek.show');
        Route::post('/store', [ProyekPekerjaanController::class, 'store'])->name('pekerjaan-proyek.store');
        Route::post('/store-single', [ProyekPekerjaanController::class, 'storeSingle'])->name('pekerjaan-proyek.store-single');
        Route::delete('/destroy/{id}', [ProyekPekerjaanController::class, 'destroy'])->name('pekerjaan-proyek.destroy');
    });
    // Detail Pekerjaan Proyek
    Route::group([
        'prefix' => 'pekerjaan-proyek-detail'
    ], function(){
        Route::get('/datatable/{id}', [ProyekPekerjaanDetailController::class, 'datatable'])->name('detail-pekerjaan-proyek.datatable');
        Route::get('/{id}', [ProyekPekerjaanDetailController::class, 'index'])->name('detail-pekerjaan-proyek.index');
        Route::get('/show/{id}', [ProyekPekerjaanDetailController::class, 'show'])->name('detail-pekerjaan-proyek.show');
        Route::get('/rincian/{id}', [ProyekPekerjaanDetailController::class, 'getRincianAjax'])->name('detail-pekerjaan-proyek.index.ajax');
        Route::get('/sub-pekerjaan/show/{id}', [ProyekPekerjaanDetailController::class, 'show'])->name('detail-pekerjaan-proyek.sub-pekerjaan.show');
        Route::post('/sub-pekerjaan/store', [ProyekPekerjaanDetailController::class, 'update'])->name('detail-pekerjaan-proyek.sub-pekerjaan.store');
        Route::delete('/sub-pekerjaan/destroy/{id}', [ProyekPekerjaanDetailController::class, 'destroy'])->name('detail-pekerjaan-proyek.sub-pekerjaan.destroy');
        // Detail Sub Pekerjaan Proyek
        Route::get('/sub-pekerjaan/datatable/jasa/{id}', [ProyekSubPekerjaanDetailController::class, 'jasaDatatable'])->name('detail-pekerjaan-proyek.sub-pekerjaan.jasa.datatable');
        Route::get('/sub-pekerjaan/datatable/material/{id}', [ProyekSubPekerjaanDetailController::class, 'materialDatatable'])->name('detail-pekerjaan-proyek.sub-pekerjaan.material.datatable');
        Route::get('/sub-pekerjaan/{id}', [ProyekSubPekerjaanDetailController::class, 'index'])->name('detail-pekerjaan-proyek.sub-pekerjaan.index');
        // Harga Komponen
        Route::group([
            'as' => 'pekerjaan-proyek-detail.sub-pekerjaan.jasa.' 
        ], function(){
            // Harga Komponen Jasa Sub Pekerjaan Proyek
            Route::resource('/sub-pekerjaan/jasa/resource', ProyekHargaKomponenJasaController::class);
        });
        Route::group([
            'as' => 'pekerjaan-proyek-detail.sub-pekerjaan.material.' 
        ], function(){
            // Harga Komponen Material Sub Pekerjaan Proyek
            Route::resource('/sub-pekerjaan/material/resource', ProyekHargaKomponenMaterialController::class);
        });

        Route::get('rab/{id}', [RabController::class, 'index'])->name('rab');
    });
});
