<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenetapanPembayaran;
use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Kelas;

class DashboardController extends Controller
{
    public function index()
    {
        $pesertas = Peserta::all();
        $kelass = Kelas::all();
        $totalPeserta = $pesertas->count(); // Hitung jumlah peserta
        $totalPembayaranDiterima = PenetapanPembayaran::where('debet_kredit', 'KR')
            ->sum('jumlah_bayar');

        return view('admin.dashboard', compact('pesertas', 'kelass', 'totalPeserta', 'totalPembayaranDiterima'));
    }
}
