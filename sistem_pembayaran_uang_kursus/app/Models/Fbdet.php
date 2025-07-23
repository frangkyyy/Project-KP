<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fbdet extends Model
{
    use HasFactory;

    protected $table = 'fbdet';

    public $timestamps = false; 

    protected $fillable = [
        'NoFB',
        'Seq',
        'KodeBarang',
        'Qty',
        'Harga',
        'Subtotal',
        'created_at',
    ];

    // protected $keyType = 'string';

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'KodeBarang', 'KodeBarang');
    }
}
