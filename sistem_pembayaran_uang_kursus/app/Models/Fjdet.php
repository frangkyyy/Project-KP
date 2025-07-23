<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fjdet extends Model
{
    use HasFactory;

    protected $table = 'fjdet';

    public $timestamps = false; 

    protected $fillable = [
        'NoFJ',
        'Seq',
        'KodeBarang',
        'Qty',
        'Subtotal',
        'created_at',
    ];

    // protected $keyType = 'string';
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'KodeBarang', 'KodeBarang');
    }
}
