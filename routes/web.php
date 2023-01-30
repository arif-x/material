<?php

use Illuminate\Support\Facades\Route;

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
        Route::get('/harga-material/{id}', [App\Http\Controllers\Data\GetHargaController::class, 'material'])->name('get-harga-material');  
        Route::get('/harga-jasa/{id}', [App\Http\Controllers\Data\GetHargaController::class, 'jasa'])->name('get-harga-jasa');
    });
    // Material
    Route::group([
        'prefix' => 'material',
        'as' => 'admin.master.material.'
    ], function(){
        Route::resource('/jenis-material', App\Http\Controllers\Master\Material\JenisMaterialController::class);  
        Route::resource('/satuan-material', App\Http\Controllers\Master\Material\SatuanMaterialController::class);   
        Route::resource('/material', App\Http\Controllers\Master\Material\MaterialController::class);  
    });
    // Pekerjaan
    Route::group([
        'prefix' => 'pekerjaan',
        'as' => 'admin.master.pekerjaan.'
    ], function(){
        Route::resource('/pekerjaan', App\Http\Controllers\Master\Pekerjaan\PekerjaanController::class);
        Route::resource('/sub-pekerjaan', App\Http\Controllers\Master\Pekerjaan\SubPekerjaanController::class);
    });
    // Jasa
    Route::group([
        'prefix' => 'jasa',
        'as' => 'admin.master.jasa.'
    ], function(){
        Route::resource('/jenis-jasa', App\Http\Controllers\Master\Jasa\JenisJasaController::class);  
        Route::resource('/satuan-jasa', App\Http\Controllers\Master\Jasa\SatuanJasaController::class);   
        Route::resource('/jasa', App\Http\Controllers\Master\Jasa\JasaController::class);  
    });
    // Harga Komponen
    Route::group([
        'prefix' => 'harga-komponen',
        'as' => 'admin.master.harga-komponen.'
    ], function(){
        Route::resource('/harga-komponen-jasa', App\Http\Controllers\Master\HargaKomponen\HargaKomponenJasaController::class);
        Route::resource('/harga-komponen-material', App\Http\Controllers\Master\HargaKomponen\HargaKomponenMaterialController::class);
    });
});
