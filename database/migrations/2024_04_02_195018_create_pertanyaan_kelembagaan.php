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
        Schema::create('pertanyaan_kelembagaan', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan');
            $table->foreignId('id_kelembagaan')->constrained('kelembagaan');
            $table->string('jawaban_a');
            $table->string('jawaban_b');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_kelembagaan');
    }
};
