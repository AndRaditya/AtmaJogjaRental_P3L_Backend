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
        Schema::create('transaksi__mobil_10144s', function (Blueprint $table) {
            $table->id('id_transaksi_increment');
            $table->string('id_transaksi_mobil', 50);
            $table->unsignedBigInteger('id_customer_increment')->index();
            $table->unsignedBigInteger('id_pegawai')->index();
            $table->unsignedBigInteger('id_promo')->index()->nullable();

            $table->string('status_transaksi', 30);
            $table->string('bukti_transfer', 255)->nullable();
            $table->string('status_dokumen', 100);
            $table->date('tanggal_transaksi');
            $table->string('metode_pembayaran', 20);
            $table->timestamps();
        });

        Schema::table('transaksi__mobil_10144s', function ($table) {
            $table->foreign('id_customer_increment')
                ->references('id_customer_increment')->on('customer_10144s');
            $table->foreign('id_pegawai')
                ->references('id_pegawai')->on('pegawai_10144s');
            $table->foreign('id_promo')
                ->references('id_promo')->on('promo_10144s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi__mobil_10144s');
    }
};