<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_materials', function (Blueprint $table) {
            $table->id();
            $table->string('kode_material');
            $table->string('nama_material');
            $table->unsignedBigInteger('jenis_material_id');
            $table->foreign('jenis_material_id')->references('id')->on('jenis_materials')->onDelete('cascade');
            $table->unsignedBigInteger('satuan_material_id');
            $table->foreign('satuan_material_id')->references('id')->on('satuan_materials')->onDelete('cascade');
            $table->integer('harga_beli');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_materials');
    }
}
