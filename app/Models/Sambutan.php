<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sambutan extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (tanpa jamak/s)
    protected $table = 'sambutan';

    protected $fillable = [
    'acara',
    'tanggal',
    'waktu',
    'tempat',
    'penyusun',
    'penyelenggara',
    'jenis_surat', // <-- Tambahkan ini
    'link',
    'gambar',
    'status',
];
}