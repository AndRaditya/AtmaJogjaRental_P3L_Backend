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
        Schema::create('aset__mobil_10144s', function (Blueprint $table) {
            $table->id('id_aset_mobil');
            $table->unsignedBigInteger('id_pemilik_mobil')->index()->nullable();

            $table->string('nama_mobil', 15);
            $table->string('tipe_mobil', 20);
            $table->string('plat_nomor_mobil', 20);
            $table->string('jenis_transmisi_mobil', 20);
            $table->string('jenis_bahanbakar_mobil', 20);
            $table->integer('volume_bahanbakar_mobil');
            $table->string('warna_mobil', 20);
            $table->integer('kapasitas_penumpang_mobil');
            $table->string('fasilitas_mobil', 100);
            $table->integer('nomor_stnk_mobil');
            $table->double('harga_sewa_mobil', 10);
            $table->integer('volume_bagasi_mobil');
            $table->timestamps();
        });

        Schema::table('aset__mobil_10144s', function ($table) {
            $table->foreign('id_pemilik_mobil')
                ->references('id_pemilik_mobil')
                ->on('pemilik__mobil_10144s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aset__mobil_10144s');
    }
};