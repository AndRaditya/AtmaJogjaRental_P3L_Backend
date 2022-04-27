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
        Schema::create('driver_10144s', function (Blueprint $table) {
            $table->id('id_driver_increment');
            $table->string('id_driver',50);
        
            $table->string('nama_driver',50);
            $table->string('alamat_driver',200);
            $table->date('tanggal_lahir_driver');
            $table->string('jenis_kelamin_driver',10);
            $table->string('email_driver',50);
            $table->string('nomor_telepon_driver',12);
            $table->string('bahasa_driver',1024)->change();
            $table->string('foto_driver',255);
            $table->double('tarif_driver_harian',50);
            $table->string('status_driver',10);
            $table->string('password_driver',20);
            $table->double('rerata_rating',20);
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
        Schema::dropIfExists('driver_10144s');
    }
};
