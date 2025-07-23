<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifKelas extends Model
{
    use HasFactory;

    protected $table = 'tarif_kelas';

    protected $fillable = [
        'id_tarif',
        'id_kelas',
        'bulan',
        'tahun',
        'tarif',
        'tarif_daftar'
    ];

    protected $primaryKey = 'id_tarif';
}
