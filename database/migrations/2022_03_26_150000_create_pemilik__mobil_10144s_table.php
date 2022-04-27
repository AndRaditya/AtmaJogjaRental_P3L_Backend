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
        Schema::create('pemilik__mobil_10144s', function (Blueprint $table) {
            $table->id('id_pemilik_mobil');
            $table->string('nama_pemilik_mobil',50);
            $table->string('no_ktp_pemilik_mobil',16);
            $table->string('alamat_pemilik_mobil',200);
            $table->string('nomor_telepon_pemilik_mobil',13);
            $table->date('periode_kontrak_mulai_mobil');
            $table->date('periode_kontrak_akhir_mobil');
            $table->date('tanggal_terakhir_servis_mobil');
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
        Schema::dropIfExists('pemilik__mobil_10144s');
    }
};
