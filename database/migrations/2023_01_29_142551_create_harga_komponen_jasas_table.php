<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaKomponenJasasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('harga_komponen_jasas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jasa_id');
            $table->foreign('jasa_id')->references('id')->on('master_jasas')->onDelete('cascade');
            $table->unsignedBigInteger('sub_pekerjaan_id');
            $table->foreign('sub_pekerjaan_id')->references('id')->on('master_sub_pekerjaans')->onDelete('cascade');
            $table->string('koefisien');
            $table->integer('harga_komponen_jasa');
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
        Schema::dropIfExists('harga_komponen_jasas');
    }
}
