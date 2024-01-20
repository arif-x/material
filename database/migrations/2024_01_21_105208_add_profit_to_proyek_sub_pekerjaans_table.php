<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfitToProyekSubPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyek_sub_pekerjaans', function (Blueprint $table) {
            $table->string('profit')->after('sub_pekerjaan_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proyek_sub_pekerjaans', function (Blueprint $table) {
            $table->dropColumn('profit');
        });
    }
}
