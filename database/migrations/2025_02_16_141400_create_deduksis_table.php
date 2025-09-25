<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeduksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // izin, terlambat, mangkir, bpjs kesehatan, bpjs ketenagakerjaan
            $table->double('nominal')->default(0);
            $table->string('keterangan');
//            $table->integer('izin')->nullable();
//            $table->integer('terlambat')->nullable();
//            $table->integer('mangkir')->nullable();
//            $table->integer('bpjs_kesehatan')->nullable();
//            $table->integer('bpjs_ketenagakerjaan')->nullable();
//            $table->integer('saldo_kasbon')->nullable();
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
        Schema::dropIfExists('deduksis');
    }
}
