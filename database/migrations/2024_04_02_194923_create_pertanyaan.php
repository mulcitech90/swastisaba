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
        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan');
            $table->foreignId('indikator_id')->constrained('indikator');
            $table->foreignId('dinas_id')->constrained('dinas');
            $table->foreignId('kab_kota_id')->constrained('kab_kota');
            $table->foreignId('provinsi_id')->constrained('provinsi');
            $table->foreignId('tatanan_id')->constrained('tatanan');
            $table->foreignId('user_id')->constrained('users');
            $table->string('status');
            $table->string('jawaban_a');
            $table->string('jawaban_b');
            $table->string('jawaban_c');
            $table->string('jawaban_d');
            // nilai a, b, c, d
            $table->integer('nilai_a');
            $table->integer('nilai_b');
            $table->integer('nilai_c');
            $table->integer('nilai_d');
            // KAT bool
            $table->boolean('kat')->default(false);
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaan');
    }
};
