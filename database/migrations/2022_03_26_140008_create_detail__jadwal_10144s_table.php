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
        Schema::create('detail__jadwal_10144s', function (Blueprint $table) {
            $table->id('id_detail_jadwal');
            $table->unsignedBigInteger('id_pegawai');
            $table->unsignedBigInteger('id_jadwal_increment')->nullable();

            $table->string('keterangan_jadwal', 255);
            $table->timestamps();
        });

        Schema::table('detail__jadwal_10144s', function ($table) {
            $table->foreign('id_pegawai')
                ->references('id_pegawai')->on('pegawai_10144s');
            $table->foreign('id_jadwal_increment')
                ->references('id_jadwal_increment')->on('jadwal__pegawai_10144s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail__jadwal_10144s');
    }
};