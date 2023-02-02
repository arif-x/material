<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyekHargaKomponenMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyek_harga_komponen_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyek_sub_pekerjaan_id');
            $table->foreign('proyek_sub_pekerjaan_id')->references('id')->on('proyek_sub_pekerjaans')->onDelete('cascade');

            $table->unsignedBigInteger('material_id');
            $table->foreign('material_id')->references('id')->on('master_materials')->onDelete('cascade');

            $table->bigInteger('harga_asli');
            $table->string('koefisien');
            $table->bigInteger('harga_fix');
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
        Schema::dropIfExists('proyek_harga_komponen_materials');
    }
}
