<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'KodeBarang';
    public $incrementing = false;    

    public $timestamps = false; 

    protected $fillable = [
        'KodeBarang',
        'NamaBarang',
        'KodeTipeBarang',
        'Harga',
        'HargaBeli',
        'HargaDaftar',
        'Satuan',
        'Stok',
        'Keterangan'
    ];

    protected $keyType = 'string';

    public function tipebarang()
    {
        return $this->belongsTo(TipeBarang::class, 'KodeTipeBarang', 'KodeTipeBarang');
    }

    public function fbdet()
    {
        return $this->hasMany(Fbdet::class, 'KodeBarang', 'KodeBarang');
    }

    public function fjdet()
    {
        return $this->hasMany(Fjdet::class, 'KodeBarang', 'KodeBarang');
    }
}
