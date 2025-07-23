<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenetapanPembayaran extends Model
{
    use HasFactory;

    protected $table = 'penetapan_dan_pembayaran';

    protected $fillable = [
        'id_pembayaran',
        'id_peserta',
        'id_kursus',
        'id_kelas',
        'tanggal_transaksi',
        'jumlah_bayar',
        'debet_kredit',
        'jenis_pembayaran',
        'sistem_pembayaran',
        'id_penetapan',
        'bulan',
        'tahun',
        'keterangan',
        'status_siswa'
    ];

    protected $primaryKey = 'id_pembayaran';

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta', 'id_peserta');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class, 'id_kursus');
    }
}


