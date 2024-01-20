<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfitToMasterSubPekerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_sub_pekerjaans', function (Blueprint $table) {
            $table->string('profit')->after('nama_sub_pekerjaan')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_sub_pekerjaans', function (Blueprint $table) {
            $table->dropColumn('profit');
        });
    }
}
