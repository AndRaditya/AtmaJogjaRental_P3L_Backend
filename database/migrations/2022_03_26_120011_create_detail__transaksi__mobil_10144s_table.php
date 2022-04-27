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
        Schema::create('detail__transaksi__mobil_10144s', function (Blueprint $table) {
            $table->id('id_detailTrs_increment');
            $table->string('id_detail_transaksi_mobil',50);
            $table->unsignedBigInteger('id_aset_mobil')->index();
            $table->unsignedBigInteger('id_driver_increment')->index()->nullable();
            $table->unsignedBigInteger('id_transaksi_increment')->index();
            
            $table->dateTime('tanggal_pengembalian');
            $table->dateTime('tanggal_waktu_mulaiSewa');
            $table->dateTime('tanggal_waktu_selesaiSewa');
            $table->double('rating_driver',20)->nullable();
            $table->string('jenis_transaksi',100);
            $table->double('jumlah_pembayaran',10);
            $table->double('denda',20);
            $table->timestamps();
        });

        Schema::table('detail__transaksi__mobil_10144s', function($table)
        {
            $table->foreign('id_aset_mobil')
                ->references('id_aset_mobil')->on('aset__mobil_10144s');
            $table->foreign('id_driver_increment')
                ->references('id_driver_increment')->on('driver_10144s');
            $table->foreign('id_transaksi_increment')
                ->references('id_transaksi_increment')->on('transaksi__mobil_10144s');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail__transaksi__mobil_10144s');
    }
};
