<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    public $timestamps = false;


    protected $fillable = [
        'KodeSupplier',
        'NamaSupplier',
    ];

    // protected $keyType = 'string';

    /**
     * Relasi One to Many dengan model Pembelian
     */
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'KodeSupplier', 'KodeSupplier');
    }

}
