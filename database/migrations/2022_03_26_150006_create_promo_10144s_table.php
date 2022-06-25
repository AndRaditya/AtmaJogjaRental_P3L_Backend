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
        Schema::create('promo_10144s', function (Blueprint $table) {
            $table->id('id_promo');
            $table->string('kode_promo', 10)->nullable();
            $table->string('jenis_promo', 20)->nullable();
            $table->string('keterangan_promo', 200)->nullable();
            $table->float('diskon', 5)->nullable();
            $table->string('status_promo', 20)->nullable();
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
        Schema::dropIfExists('promo_10144s');
    }
};