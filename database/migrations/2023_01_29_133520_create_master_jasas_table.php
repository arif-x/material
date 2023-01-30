<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterJasasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_jasas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_jasa_id');
            $table->foreign('jenis_jasa_id')->references('id')->on('jenis_jasas')->onDelete('cascade');
            $table->unsignedBigInteger('satuan_jasa_id');
            $table->foreign('satuan_jasa_id')->references('id')->on('satuan_jasas')->onDelete('cascade');
            $table->string('kode_jasa');
            $table->string('nama_jasa');
            $table->integer('harga_jasa');
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
        Schema::dropIfExists('jasas');
    }
}
