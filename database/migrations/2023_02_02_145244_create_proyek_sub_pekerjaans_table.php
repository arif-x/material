<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyekSubPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyek_sub_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyek_pekerjaan_id');
            $table->foreign('proyek_pekerjaan_id')->references('id')->on('proyek_pekerjaans')->onDelete('cascade');

            $table->unsignedBigInteger('sub_pekerjaan_id');
            $table->foreign('sub_pekerjaan_id')->references('id')->on('master_sub_pekerjaans')->onDelete('cascade');

            $table->string('volume');

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
        Schema::dropIfExists('proyek_sub_pekerjaans');
    }
}
