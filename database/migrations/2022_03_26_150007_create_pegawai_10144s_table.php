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
        Schema::create('pegawai_10144s', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->unsignedBigInteger('id_jabatan')->index();

            $table->string('nama_pegawai', 100);
            $table->string('alamat_pegawai', 200);
            $table->date('tanggal_lahir_pegawai');
            $table->string('jenis_kelamin_pegawai', 10);
            $table->string('email_pegawai', 50);
            $table->string('nomor_telepon_pegawai', 13);
            $table->string('password_pegawai', 20);
            $table->string('foto_pegawai', 255);
            $table->timestamps();
        });

        Schema::table('pegawai_10144s', function ($table) {
            $table->foreign('id_jabatan')
                ->references('id_jabatan')->on('jabatan__pegawai_10144s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pegawai_10144s');
    }
};