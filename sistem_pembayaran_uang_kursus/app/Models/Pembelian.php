<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'fb';
    protected $primaryKey = 'NoFB';
    public $incrementing = false;


    public $timestamps = false;

    protected $fillable = [
        'NoFB', 'KodeSupplier', 'TotalFaktur', 'TglFB', 'UangMuka', 'Piutang', 'Keterangan',
        'Subtotal',
        'Tempo', 'Discount',
          'Keterangan2', 'Keterangan3', 'Posted',
         'Lunas', 'NoSJ',
    ];

    protected $casts = [
        'Tempo' => 'integer',
    ];

    /**
     * Relasi Many to One dengan model Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'KodeSupplier', 'KodeSupplier');
    }

    /**
     * Relasi Many to One dengan model Peserta (Siswa)
     */
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta', 'id_peserta');
    }
    

    /**
     * Menghitung total setelah diskon
     */
    public function hitungTotal()
    {
        return $this->Subtotal - $this->Discount;
    }


    /**
     * Relasi one-to-many dengan model fbbayar
     */
    public function fbbayar()
    {
        return $this->hasMany(Fbbayar::class, 'NoFB', 'NoFB');
    }

    public function pembayaranHutangs()
    {
        return $this->hasMany(Fbbayar::class, 'NoFB', 'NoFB');
    }

    public function items()
    {
        return $this->hasMany(Fbdet::class, 'NoFB', 'NoFB');
    }

    
}
