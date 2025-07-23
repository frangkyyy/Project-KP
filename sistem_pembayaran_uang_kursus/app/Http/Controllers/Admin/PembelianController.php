<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\Fbbayar;
use App\Models\Barang;
use App\Models\TipeBarang;
use App\Models\Fbdet;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with('supplier', 'fbbayar', 'items.barang')->get(); 
        $barang = Barang::all();
        $suppliers = Supplier::all();
        $users = auth()->user();

        return view('admin.pembelian.index', compact('pembelians', 'barang', 'suppliers', 'users'));
    }
    

    public function create()
    {
        $lastInvoice = Pembelian::orderBy('NoFB', 'desc')->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->NoFB, 3); 
            $newNumber = 'Fb-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = 'Fb-00001'; 
        }

        $lastSupplier = Supplier::orderBy('KodeSupplier', 'desc')->first();
        $lastKodeSupplier = $lastSupplier ? $lastSupplier->KodeSupplier : 'S-00001';
        $nextKodeSupplier = 'S-' . str_pad(substr($lastKodeSupplier, 2) + 1, 5, '0', STR_PAD_LEFT);

        // Ambil data barang untuk dropdown
        $barang = Barang::all();
        $suppliers = Supplier::all();
        $tipeBarangs = TipeBarang::all();

        $lastKodeBarang = Barang::orderBy('KodeBarang', 'desc')->first()->KodeBarang ?? 'B-00000';
        $lastNumber = (int) substr($lastKodeBarang, 2);
        $nextNumber = $lastNumber + 1;
        $nextKodeBarang = 'B-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Tampilkan form pembelian
        return view('admin.pembelian.create', compact('newNumber', 'nextKodeSupplier', 'barang', 'suppliers', 'nextKodeBarang', 'tipeBarangs'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validasi input
        $request->validate([
            'NoFB' => 'required|string|max:255|unique:fb,NoFB',
            'TglFB' => 'required|date',
            'KodeSupplier' => 'required|exists:supplier,KodeSupplier|max:10',
            'TotalFaktur' => 'required|numeric',
            'UangMuka' => 'nullable|numeric|min:0',
            'item' => 'required|array',
            'item.*' => 'required|string|max:255',
            'quantity' => 'required|array|size:' . count($request->item),
            'quantity.*' => 'numeric|min:1',
            'price' => 'required|array|size:' . count($request->item),
            'price.*' => 'numeric|min:1',
            'kode_barang' => 'required|array|size:' . count($request->item),
            'kode_barang.*' => 'required|string|max:10',
            'tipe_barang' => 'required|array|size:' . count($request->item),
            'tipe_barang.*' => 'nullable|string|max:10',
            'created_at' => 'nullable|date',
            'tempo' => 'nullable|integer|min:1',
            'Keterangan3' => 'nullable|string|max:20',
            'jatuh_tempo' => 'nullable|date|min:1',

        ]);

        try {
            $tempoHari = $request->tempo ?? 30;

            // Proses penyimpanan data
            Pembelian::create([
                'NoFB' => $request->NoFB,
                'TglFB' => $request->TglFB,
                'KodeSupplier' => $request->KodeSupplier,
                'UangMuka' => $request->UangMuka,
                'TotalFaktur' => $request->TotalFaktur,
                'Piutang' => $request->TotalFaktur - $request->UangMuka,
                'Keterangan' => ($request->TotalFaktur - $request->UangMuka) == 0 ? 'Lunas' : 'Menunggu pelunasan',
                'Tempo' => $tempoHari,
                'Keterangan2' => $request->jatuh_tempo,
                'Keterangan3' => "{$request->payment_method} - {$request->Keterangan3}",
            ]);

            // Fbbayar::create([
            //     'Transfer' => $request->Transfer,
            // ]);

        
            // Simpan detail barang dan update stok
            foreach ($request->item as $index => $item) {
                $barang = Barang::where('NamaBarang', $item)->first();
                $kodeBarang = null; // Initialize the variable
            
                if ($barang) {
                    $kodeBarang = $barang->KodeBarang;
                    // Jika barang ditemukan, update stok
                    $barang->Stok += $request->quantity[$index];
                    $barang->save();
                } else {
                    // Jika barang tidak ditemukan, tambahkan data baru
                    $kodeBarang = $request->kode_barang[$index]; 
                    
                    Barang::create([
                        'KodeBarang' => $kodeBarang,
                        'NamaBarang' => $item,
                        'Stok' => $request->quantity[$index],
                        'HargaBeli' => $request->price[$index],
                        'KodeTipeBarang' => $request->tipe_barang[$index] ?? null,
                    ]);
                }
            
                if ($kodeBarang) {
                    $lastSeq = Fbdet::where('NoFB', $request->NoFB)->max('Seq');
                    $seq = $lastSeq ? $lastSeq + 1 : 1;
            
                    Fbdet::create([
                        'NoFB' => $request->NoFB,
                        'KodeBarang' => $kodeBarang,
                        'Qty' => $request->quantity[$index],
                        'Seq' => $seq,
                        'Subtotal' => $request->price[$index],
                        'created_at' => $request->TglFB,
                    ]);
                } else {
                    // Jika kodeBarang tidak valid, bisa memberikan pesan error
                    return back()->with('error', 'Kode barang tidak valid.');
                }
            }

            return redirect()->route('admin.pembelian.index')->with([
                'message' => 'Pembelian berhasil ditambahkan!',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        
    }
    
    
        /**
     * Show the form for editing the specified resource.
     *
     * @param  Pembelian  $NoFB
     * @return \Illuminate\Http\Response
     */
    public function edit($NoFB)
    {
        
        $pembelian = Pembelian::findOrFail($NoFB); 
        $pembelian->TglFB = \Carbon\Carbon::parse($pembelian->TglFB);

        $suppliers = Supplier::all(); 
        return view('admin.pembelian.edit', compact('pembelian', 'suppliers'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Pembelian  $NoFB
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $NoFB)
    {

        $request->validate([
            'TglFB' => 'required|date',
            'KodeSupplier' => 'required|exists:supplier,KodeSupplier',
            'TotalFaktur' => 'required|numeric|min:0',
            'UangMuka' => 'required|numeric|min:0',
            'Piutang' => 'required|numeric|min:0',
        ]);

        $pembelian = Pembelian::findOrFail($NoFB);
        $pembelian->KodeSupplier = $pembelian->supplier->KodeSupplier;

        $Piutang = $request->TotalFaktur - $request->UangMuka;
        $keterangan = ($Piutang == 0) ? 'Lunas' : '';

        $pembelian->update([
            'TglFB' => $request->TglFB,
            'KodeSupplier' => $request->KodeSupplier,
            'TotalFaktur' => $request->TotalFaktur,
            'UangMuka' => $request->UangMuka,
            'Piutang' => $request->TotalFaktur - $request->UangMuka,
            'Keterangan' => $request->$keterangan,
        ]);

        return redirect()->route('admin.pembelian.index')->with('success', 'Data faktur berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Pembelian  $pembelian
     * @return \Illuminate\Http\Response
     */
    public function destroy($NoFB)
    {
        $pembelian = Pembelian::findOrFail($NoFB);
        $pembelian->delete();

        return redirect()->route('admin.pembelian.index')->with('success', 'Data berhasil dihapus.');
    }

    public function bayarHutang(Request $request)
    {
        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'NoFB' => 'required|exists:fb,NoFB',
        ]);
    
        $pembelian = Pembelian::where('NoFB', $request->NoFB)->firstOrFail();
    
        if ($pembelian->Piutang > 0 && $request->jumlah_bayar > 0) {
            $pembelian->Piutang -= $request->jumlah_bayar;
    
            if ($pembelian->Piutang == 0) {
                $pembelian->Keterangan = 'Lunas';
            }
    
            $pembelian->save();

            Fbbayar::create([
                'NoFB' => $request->NoFB,
                'TglBayar' => now(), 
                'KodeSupplier' => $pembelian->KodeSupplier,
                'TotalBayar' => $request->jumlah_bayar,
                'SaldoSisaPiutang' => $pembelian->Piutang,
                'MetodePembayaran' => $request->metode_pembayaran,
                'Keterangan' => $request->keterangan,
            ]);
        }
    
        return redirect()->route('admin.pembelian.index')->with('success', 'Pembayaran hutang berhasil.');
    }  
    
    public function getRiwayatPembayaran($NoFB)
    {
        $riwayat = Fbbayar::where('NoFB', $NoFB)->get();
    
        if ($riwayat->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'Belum ada riwayat pembayaran.'], 404);
        }
    
        return response()->json(['data' => $riwayat], 200);
    }

    public function laporanPembelian(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
        ]);
    
        $startDate = $validated['start'];
        $endDate = $validated['end'];
    
        // Ambil data pembelian dari Fbdet
        $pembelian = Fbdet::whereBetween('created_at', [$startDate, $endDate])->get();
    
        if ($pembelian->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data Pembelian untuk rentang tanggal ini.',
                'dataPembelian' => [],
                'totalPembelian' => 0
            ]);
        }
    
        $dataPembelian = [];
        $totalPembelian = 0;
    
        // Memasukkan data pembelian berdasarkan NoFB
        foreach ($pembelian as $Pembelian) {
            // Jika NoFB belum ada, buat entri baru
            if (!isset($dataPembelian[$Pembelian->NoFB])) {
                $dataPembelian[$Pembelian->NoFB] = [
                    'NoFB' => $Pembelian->NoFB,
                    'items' => []
                ];
            }
    
            // Tambahkan data item dengan Subtotal yang dihitung
            $dataPembelian[$Pembelian->NoFB]['items'][] = [
                'Seq' => $Pembelian->Seq,
                'KodeBarang' => $Pembelian->KodeBarang,
                'Qty' => $Pembelian->Qty,
                'Subtotal' => $Pembelian->Subtotal * $Pembelian->Qty, 
            ];
    
            // Tambahkan Subtotal ke Total Pembelian

            $totalPembelian = 0;

            // Menghitung total pembelian
            foreach ($dataPembelian as $pembelian) {
                foreach ($pembelian['items'] as $item) {
                    $totalPembelian += $item['Subtotal'];
                }
            }
        }
    
        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'dataPembelian' => array_values($dataPembelian), // Mengubah array asosiatif menjadi numerik
            'totalPembelian' => $totalPembelian,
        ]);
    }
    
}
