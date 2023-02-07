<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterSubPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_sub_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pekerjaan_id');
            $table->foreign('pekerjaan_id')->references('id')->on('master_pekerjaans')->onDelete('cascade');
            $table->unsignedBigInteger('satuan_sub_pekerjaan_id');
            $table->foreign('satuan_sub_pekerjaan_id')->references('id')->on('satuan_sub_pekerjaans')->onDelete('cascade');
            $table->string('kode_sub_pekerjaan');
            $table->string('nama_sub_pekerjaan');
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
        Schema::dropIfExists('master_sub_pekerjaans');
    }
}
