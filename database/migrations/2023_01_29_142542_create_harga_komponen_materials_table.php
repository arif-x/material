<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaKomponenMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('harga_komponen_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->foreign('material_id')->references('id')->on('master_materials')->onDelete('cascade');
            $table->unsignedBigInteger('sub_pekerjaan_id');
            $table->foreign('sub_pekerjaan_id')->references('id')->on('master_sub_pekerjaans')->onDelete('cascade');
            $table->string('koefisien');
            $table->integer('harga_komponen_material');
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
        Schema::dropIfExists('harga_komponen_materials');
    }
}
