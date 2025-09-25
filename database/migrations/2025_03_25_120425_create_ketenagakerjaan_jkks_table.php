<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetenagakerjaanJkksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ketenagakerjaan_jkks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
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
        Schema::dropIfExists('ketenagakerjaan_jkks');
    }
}
