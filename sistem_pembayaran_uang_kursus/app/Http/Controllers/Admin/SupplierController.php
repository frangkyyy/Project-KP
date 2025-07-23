<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'KodeSupplier' => 'required|unique:supplier,KodeSupplier|max:50',
            'NamaSupplier' => 'required|max:100',
        ]);

        Supplier::create([
            'KodeSupplier' => $request->KodeSupplier,
            'NamaSupplier' => $request->NamaSupplier,
        ]);

        return redirect()->back()->with('success', 'Tipe Barang berhasil ditambahkan.');
    }
}
