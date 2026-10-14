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
        Schema::create('sambutan', function (Blueprint $table) {
            $table->id();
            $table->string('acara');
            $table->date('tanggal');
            $table->string('waktu'); // contoh: '08:00 WIB'
            $table->string('tempat');
            $table->string('penyusun')->nullable();
            $table->text('penyelenggara');
            
            // Kolom Jenis Surat
            $table->enum('jenis_surat', ['Sambutan', 'Keprotokolan', 'Sambutan dan Keprotokolan'])->nullable();
            
            $table->string('link')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('status', ['Publik', 'Privat'])->default('Publik');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sambutan');
    }
};