<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keprotokolan extends Model
{
    use HasFactory;

    protected $table = 'keprotokolan';

    // Bebaskan semua kolom agar diizinkan untuk diisi
    protected $guarded = [];
}