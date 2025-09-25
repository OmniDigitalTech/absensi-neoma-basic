<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('golongan_id');
            $table->decimal('gaji_pokok', 15, 2)->default(2);
            $table->decimal('kehadiran', 15, 2)->default(2);
            $table->decimal('lembur', 15, 2)->default(2);
            $table->decimal('oncall', 15, 2)->default(2);
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
        Schema::dropIfExists('upahs');
    }
}
