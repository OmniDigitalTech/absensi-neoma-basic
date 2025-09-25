<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('alamat')->nullable();
            $table->string('alamat_ttd')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('bpjs_kesehatan', ['ya', 'tidak'])->default('tidak');
            $table->enum('bpjs_kesehatan_kontrak', ['ya', 'tidak'])->default('tidak');
            $table->enum('bpjs_ketenagakerjaan', ['ya', 'tidak'])->default('tidak');
            $table->foreignId('bpjs_ketenagakerjaan_jht')->nullable()->default(null);
            $table->foreignId('bpjs_ketenagakerjaan_jp')->nullable()->default(null);
            $table->foreignId('bpjs_ketenagakerjaan_jkp')->nullable()->default(null);
            $table->foreignId('bpjs_ketenagakerjaan_jkm')->nullable()->default(null);
            $table->foreignId('bpjs_ketenagakerjaan_jkk')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
