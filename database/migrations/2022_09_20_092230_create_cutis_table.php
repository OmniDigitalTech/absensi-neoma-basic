<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('nama_cuti');
//            $table->string('tanggal');
            $table->string('tanggal_mulai');
            $table->string('tanggal_akhir');
            $table->text('alasan_cuti');
            $table->string('foto_cuti')->nullable();
            $table->string('status_cuti');
            $table->string('approval1')->nullable();
            $table->string('approval2')->nullable();
            $table->string('approval3')->nullable();
            $table->string('catatan')->nullable();
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
        Schema::dropIfExists('cutis');
    }
}
