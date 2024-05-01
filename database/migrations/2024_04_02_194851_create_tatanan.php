<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tatanan', function (Blueprint $table) {
            $table->id();
            // id_model
            $table->unsignedBigInteger('id_model');
            $table->unsignedBigInteger('id_indikator');
            $table->string('nama_tatanan');
            $table->foreign('id_model')->references('id')->on('model');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tatanan');
    }
};
