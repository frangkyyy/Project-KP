<?php

namespace App\Http\Controllers\Admin;

use App\Models\TipeBarang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipeBarangController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'KodeTipeBarang' => 'required|unique:tipebarang,KodeTipeBarang|max:50',
            'NamaTipeBarang' => 'required|max:100',
        ]);

        TipeBarang::create([
            'KodeTipeBarang' => $request->KodeTipeBarang,
            'NamaTipeBarang' => $request->NamaTipeBarang,
        ]);

        return redirect()->back()->with('success', 'Tipe Barang berhasil ditambahkan.');
    }
}
