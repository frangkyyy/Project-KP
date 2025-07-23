<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'id_pendaftaran',
        'id_peserta',
        'id_kursus',
        'id_kelas',
        'tanggal_daftar'
    ];

    protected $primaryKey = 'id_pendaftaran';
}
