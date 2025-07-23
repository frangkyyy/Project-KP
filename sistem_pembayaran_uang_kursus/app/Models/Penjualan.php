<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'fj';
    protected $primaryKey = 'NoFJ';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'NoFJ',       
        'id_peserta', 
        'KodePelanggan',      
        'TglFJ',            
        'Subtotal',         
        'Discount',         
        'TotalFaktur',      
        'Keterangan',       
        'Keterangan2',       
        'Posted',           
        'Pembayaran',       
        'Lunas',            
    ];

    /**
     * Relasi Many to One dengan model Peserta (Siswa)
     */
    public function peserta() 
    {
        return $this->belongsTo(Peserta::class, 'id_peserta', 'id_peserta');
    }

    /**
     * Relasi Many to Many dengan model Barang
     */
    // public function barang()
    // {
    //     return $this->belongsToMany(Barang::class, 'penjualan_barang', 'penjualan_id', 'barang_id')
    //                 ->withPivot('quantity', 'price', 'subtotal')
    //                 ->withTimestamps();
    // }

    /**
     * Menghitung total setelah diskon
     */
    public function hitungTotal()
    {
        return $this->Subtotal - $this->Discount;
    }

    public function items()
    {
        return $this->hasMany(Fjdet::class, 'NoFJ', 'NoFJ');
    }
}
