<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetenagakerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ketenagakerjaans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kategori persenan (misal: Diskon, Komisi)
            $table->decimal('nominal', 5, 2); // Nilai persenan (misal: 10.50%)
            $table->string('keterangan');
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
        Schema::dropIfExists('ketenagakerjaans');
    }
}
