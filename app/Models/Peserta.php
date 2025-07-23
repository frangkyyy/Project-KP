<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'id_peserta',
        'nama',
        'alamat',
        'email',
        'tgl_daftar',
        'nohp_ortu_wali',
        'jenis_kelamin',
        'nama_panggilan',
        'tingkat_terakhir_pendidikan',
        'jadwal_belajar',
        'tmpt_lahir',
        'tgl_lahir',
        'kelas_terakhir',
        'asal_sekolah',
        'id_kelas',
        'kota',
        'kode_pos',
        'tgl_mulai_belajar',
        'lama_belajar',
        'tingkat',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'status_siswa',
        'nama_kursus',
        'status_pembayaran',

    ];

    protected $primaryKey = 'id_peserta';

    public $incrementing = false; // Tambahkan ini
    protected $keyType = 'string'; // Tambahkan ini juga untuk tipe data string

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

}
