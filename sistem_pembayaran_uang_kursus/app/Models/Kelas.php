<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'id_kelas',
        'id_kursus',
        'nama_kelas',
        'deskripsi',
        'biaya_pendaftaran',
        'biaya_bulanan_tahunan',
    ];

    protected $primaryKey = 'id_kelas';
    public $incrementing = false; // Tambahkan ini
    protected $keyType = 'string'; // Tambahkan ini juga untuk tipe data string
}
