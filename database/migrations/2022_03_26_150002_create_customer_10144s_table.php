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
        Schema::create('customer_10144s', function (Blueprint $table) {
            $table->id('id_customer_increment');
            $table->string('id_customer',50);
            
            $table->string('nama_customer',50);
            $table->string('jenis_kelamin_customer',50);
            $table->string('nomor_telepon_customer',12);
            $table->string('alamat_customer',200);
            $table->string('email_customer',50);
            $table->date('tanggal_lahir_customer');
            $table->string('no_sim_customer',16)->nullable();
            $table->string('no_ktp_customer',16);
            $table->string('password_customer',20);
            $table->integer('umur');
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
        Schema::dropIfExists('customer_10144s');
    }
};
