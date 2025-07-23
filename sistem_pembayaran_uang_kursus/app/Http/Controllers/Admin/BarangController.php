<?php

namespace App\Http\Controllers\Admin;

use App\Models\Barang;
use App\Models\TipeBarang;  
use App\Models\Fbdet;  
use App\Models\Fjdet;  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barangs = Barang::with('tipebarang')->get(); 


        return view('admin.barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipeBarangs = TipeBarang::all();

        // value baru untuk kode barang
        $lastKodeBarang = Barang::orderBy('KodeBarang', 'desc')->first()->KodeBarang ?? 'B-00001';

        $nextNumber = (int) substr($lastKodeBarang, 2) + 1;
        $nextKodeBarang = 'B-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // value baru untuk kode tipe barang
        $lastKodeBarang = TipeBarang::orderBy('KodeTipeBarang', 'desc')->first();

        if ($lastKodeBarang && is_numeric($lastKodeBarang->KodeTipeBarang)) {
            $newKodeBarang = $lastKodeBarang->KodeTipeBarang + 1;
        } else {
            $newKodeBarang = 1; 
        }
        
        return view('admin.barang.create', compact('tipeBarangs', 'nextKodeBarang', 'newKodeBarang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255', 
            'kode_barang' => 'required|string|unique:barang,KodeBarang|max:20', 
            'tipe_barang' => 'required|exists:TipeBarang,KodeTipeBarang', 
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:500',
            'harga_beli' => 'required|numeric|min:0',
        ]);

        Barang::create([
            'NamaBarang' => $request->nama_barang, 
            'KodeBarang' => $request->kode_barang,
            'KodeTipeBarang' => $request->tipe_barang, 
            'Stok' => $request->stok,
            'Keterangan' => $request->keterangan,
            'HargaBeli' => $request->harga_beli,
            'Harga' => $request->harga_beli,
            'HargaDaftar' => $request->harga_beli,
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        $tipeBarangs = TipeBarang::all();  
        return view('admin.barang.edit', compact('barang', 'tipeBarangs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $KodeBarang)
    {
        // Validasi input
        $validated = $request->validate([
            'KodeBarang' => 'required|unique:barang,KodeBarang,' . $KodeBarang . ',KodeBarang',
            'NamaBarang' => 'required|string|max:255',
            'KodeTipeBarang' => 'required|string|max:20',
            'Harga' => 'required|numeric|min:0',
            // 'Stok' => 'required|integer|min:0',
        ]);
    
        $barang = Barang::findOrFail($KodeBarang);
        
        // Update data barang
        $barang->update([
            'KodeBarang' => $request->input('KodeBarang'),
            'NamaBarang' => $request->input('NamaBarang'),
            'KodeTipeBarang' => $request->input('KodeTipeBarang'),
            'Harga' => $request->input('Harga'),
            // 'Stok' => $request->input('Stok'),
        ]);
    
        return redirect()->route('admin.barang.index')->with([
            'message' => 'Barang berhasil diperbarui!',
            'alert-type' => 'info'
        ]);
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy($KodeBarang)
    {
        $barang = Barang::findOrFail($KodeBarang);
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Data berhasil dihapus.');
    }
    /**
     * Delete all selected Barang at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        Barang::whereIn('id', $request->ids)->delete();

        return response()->noContent();
    }

    // public function show($id)
    // {
    //     $barang = Barang::findOrFail($id); // ambil data barang berdasarkan ID
    //     return view('barang.show', compact('barang'));
    // }


    public function laporanBarang(Request $request)
    {
        // Validasi input
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);
    
        // Ambil tanggal mulai dan akhir
        $startDate = $request->input('start');
        $endDate = $request->input('end');
    
        try {
            // Barang masuk (dengan Eloquent)
            $barangMasuk = Fbdet::selectRaw('KodeBarang, SUM(Qty) as Masuk');
            $barangMasuk = $barangMasuk->whereBetween('created_at', [$startDate, $endDate]);
            $barangMasuk = $barangMasuk->groupBy('KodeBarang');
            $barangMasuk = $barangMasuk->get();
    
    
            // Barang keluar (dengan Eloquent)
            $barangKeluar = Fjdet::selectRaw('KodeBarang, SUM(Qty) as Keluar');
            $barangKeluar = $barangKeluar->whereBetween('created_at', [$startDate, $endDate]);
            $barangKeluar = $barangKeluar->groupBy('KodeBarang');
            $barangKeluar = $barangKeluar->get();
    
    
            $barang = Barang::all();
    
            // Menyiapkan laporan
            $laporan = [];
    
            // Loop untuk menggabungkan data barang masuk dan keluar
            foreach ($barang as $item) {
                // Cek jumlah barang masuk untuk barang ini
                $masuk = $barangMasuk->where('KodeBarang', $item->KodeBarang)->first();
                $keluar = $barangKeluar->where('KodeBarang', $item->KodeBarang)->first();
    
                // Tambahkan data ke laporan
                $laporan[] = [
                    'KodeBarang' => $item->KodeBarang,
                    'NamaBarang' => $item->NamaBarang,
                    'Masuk' => $masuk ? $masuk->Masuk : 0,
                    'Keluar' => $keluar ? $keluar->Keluar : 0,
                ];
            }
    
            // Kembalikan data dalam format JSON
            return response()->json($laporan);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getKartuStok(Request $request, $kodeBarang)
    {
        // Ambil data dari query string
        $startDate = $request->query('start');
        $endDate = $request->query('end');
    
        // Validasi input
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);
    
        try {
            // Barang masuk
            $barangMasukQuery = Fbdet::selectRaw('DATE(created_at) as tanggal, SUM(Qty) as Masuk');
            $barangMasukQuery->where('KodeBarang', $kodeBarang);
            $barangMasukQuery->whereBetween('created_at', [$startDate, $endDate]);
            $barangMasukQuery->groupBy('tanggal');
            $barangMasuk = $barangMasukQuery->get();
    
            // Barang keluar
            $barangKeluarQuery = Fjdet::selectRaw('DATE(created_at) as tanggal, SUM(Qty) as Keluar');
            $barangKeluarQuery->where('KodeBarang', $kodeBarang);
            $barangKeluarQuery->whereBetween('created_at', [$startDate, $endDate]);
            $barangKeluarQuery->groupBy('tanggal');
            $barangKeluar = $barangKeluarQuery->get();
    
            // Gabungkan data barang masuk dan keluar berdasarkan tanggal
            $riwayat = [];
    
            foreach ($barangMasuk as $masuk) {
                $tanggal = $masuk->tanggal;
                $riwayat[$tanggal] = [
                    'tanggal' => $tanggal,
                    'masuk' => $masuk->Masuk,
                    'keluar' => 0, // Default 0, akan diperbarui jika ada data keluar
                ];
            }
    
            foreach ($barangKeluar as $keluar) {
                $tanggal = $keluar->tanggal;
                if (isset($riwayat[$tanggal])) {
                    $riwayat[$tanggal]['keluar'] = $keluar->Keluar;
                } else {
                    $riwayat[$tanggal] = [
                        'tanggal' => $tanggal,
                        'masuk' => 0, // Default 0, tidak ada data masuk pada tanggal ini
                        'keluar' => $keluar->Keluar,
                    ];
                }
            }
    
            // Ubah menjadi array numerik
            $riwayat = array_values($riwayat);
    
            // Kembalikan data dalam format JSON
            return response()->json($riwayat);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    

}
