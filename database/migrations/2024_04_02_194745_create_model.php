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
        Schema::create('model', function (Blueprint $table) {
            $table->id();
            // id_periode
            $table->unsignedBigInteger('id_periode');
            $table->string('nama_model');
            $table->foreign('id_periode')->references('id')->on('periode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model');
    }
};
