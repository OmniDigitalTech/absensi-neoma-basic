<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOncallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oncalls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('tanggal');
            $table->string('jam_masuk');
            $table->string('lat_masuk');
            $table->string('long_masuk');
            $table->string('jarak_masuk');
            $table->string('foto_jam_masuk');
            $table->string('keterangan')->nullable();
            $table->string('jam_keluar')->nullable();
            $table->string('lat_keluar')->nullable();
            $table->string('long_keluar')->nullable();
            $table->string('jarak_keluar')->nullable();
            $table->string('foto_jam_keluar')->nullable();
            $table->string('total_oncall')->nullable();
            $table->string('status')->nullable();
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
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
        Schema::dropIfExists('oncalls');
    }
}