<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_keprotokolan', function (Blueprint $table) {
            $table->id();
            
            // Nomor Pengajuan
            $table->string('no_pengajuan', 50)->nullable();
            
            // Penanggung Jawab & Acara
            $table->string('no_wa_pj', 20);
            $table->string('nama_acara');
            
            // Waktu & Tempat Pelaksanaan
            $table->string('hari_pelaksanaan', 20);
            $table->date('tanggal_pelaksanaan');
            $table->time('waktu_pelaksanaan');
            $table->text('tempat_pelaksanaan');
            
            // Detail Acara & Tamu
            $table->string('dresscode', 100);
            $table->integer('jumlah_tamu');
            $table->text('tamu_vip')->nullable();
            
            // Ketentuan Keprotokolan
            $table->enum('pendampingan_mc', ['ya', 'tidak']);
            $table->enum('permintaan_sambutan', ['ya', 'tidak']);
            $table->text('catatan_sambutan')->nullable();
            $table->enum('transit', ['tersedia', 'tidak tersedia']);
            
            // Berkas Lampiran
            $table->string('file_undangan')->nullable();
            $table->string('file_tata_tempat')->nullable();
            $table->string('susunan_acara')->nullable();
            
            // Status Verifikasi & Operator
            $table->enum('status_verifikasi', ['pending', 'terima', 'ditolak'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_keprotokolan');
    }
};