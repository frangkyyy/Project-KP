<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fbbayar extends Model
{
    use HasFactory;

    protected $table = 'fbbayar';
    public $timestamps = false; 

    protected $fillable = [
        'NoFB',
        'TglBayar',
        'TotalBayar',
        'KodeSupplier',
        'SaldoSisaPiutang',
        'MetodePembayaran',
        'Keterangan',
        'Transfer',
    ];

    public function items()
    {
        return $this->hasMany(Fbdet::class, 'NoFB', 'NoFB');
    }


    public function barang()
    {
        return $this->belongsTo(Barang::class, 'KodeBarang', 'KodeBarang');
    }
}
