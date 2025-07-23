<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeBarang extends Model
{
    use HasFactory;

    protected $table = 'tipebarang'; 
    protected $primaryKey = 'KodeTipeBarang';
    public $incrementing = false;  

    public $timestamps = false; 

    protected $fillable = [
        'KodeTipeBarang', 
        'NamaTipeBarang'
    ];

    protected $keyType = 'string';

    /**
     * Relasi dengan model Barang
     * Sebuah tipe barang bisa memiliki banyak barang.
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'KodeTipeBarang', 'KodeTipeBarang');
    }
}
