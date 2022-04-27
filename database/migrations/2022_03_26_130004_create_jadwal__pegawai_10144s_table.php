<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal__pegawai_10144s', function (Blueprint $table) {
            $table->id('id_jadwal_increment');
            $table->string('id_jadwal',50);
            $table->string('hari_shift',10);
            $table->time('waktu_shift');
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
        Schema::dropIfExists('jadwal__pegawai_10144s');
    }
};
