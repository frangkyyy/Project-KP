<?php

namespace App\Http\Controllers\Admin;

use App\Models\Penjualan;
use App\Models\Peserta;
use App\Models\TipeBarang;
use App\Models\Barang;
use App\Models\Fjdet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penjualans = Penjualan::with(['peserta', 'items.barang'])->get();
        $barang = Barang::all();
        $siswa = Peserta::all();
        $users = auth()->user();
    
        return view('admin.penjualan.index', compact('penjualans', 'barang', 'siswa', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lastInvoice = Penjualan::orderBy('NoFJ', 'desc')->first();
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->NoFJ, 3); 
            $newNumber = 'Fj-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = 'Fj-00001'; 
        }

        $lastKodePelanggan = Penjualan::orderBy('KodePelanggan', 'desc')->first();
        if ($lastKodePelanggan) {
            $lastNumber = (int) substr($lastKodePelanggan->KodePelanggan, 4);
            $newKodePelanggan = 'Pel-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newKodePelanggan = 'Pel-00001'; 
        }
        

        $pesertas = Peserta::all();

        // $tipeBarangs = TipeBarang::all();
        $barang = Barang::all();

        // Kirim data ke view
        return view('admin.penjualan.create', compact('newNumber', 'pesertas', 'barang', 'newKodePelanggan'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'NoFJ' => 'required|string|max:255|unique:fj,NoFJ',
            'TglFJ' => 'required|date',
            'KodePelanggan' => 'required|string|max:255',
            'id_peserta' => 'required|exists:peserta,id_peserta',
            'TotalFaktur' => 'required|numeric',
            'Pembayaran' => 'required|numeric',
            // 'Subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'item' => 'required|array',
            'item.*' => 'exists:barang,KodeBarang',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:1',
            'created_at' => 'nullable|date',
        ]);
    
        Penjualan::create([
            'NoFJ' => $request->NoFJ,
            'TglFJ' => $request->TglFJ,
            'KodePelanggan' => $request->KodePelanggan,
            'id_peserta' => $request->id_peserta,
            'TotalFaktur' => $request->TotalFaktur,
            // 'Subtotal' => $request->Subtotal,
            'Pembayaran' => $request->Pembayaran,
            'Keterangan' => $request->payment_method,
            'Keterangan2' => $request->Keterangan2,
            'Discount' => $request->discount,
        ]);

        foreach ($request->item as $index => $item) {
            $lastSeq = Fjdet::where('NoFJ', $request->NoFJ)->max('Seq');
            $seq = $lastSeq ? $lastSeq + 1 : 1; 
    
            Fjdet::create([
                'NoFJ' => $request->NoFJ,
                'KodeBarang' => $item,
                'Qty' => $request->quantity[$index],
                'Subtotal' => $request->price[$index],
                'Seq' => $seq,
                'created_at' => $request->TglFJ,
            ]);
            
            $barang = Barang::where('KodeBarang', $item)->first();
            if ($barang) {
                $barang->Stok -= $request->quantity[$index]; 
                $barang->save();
            }
        }
    

        return redirect()->route('admin.penjualan.index')->with([
            'message' => 'Penjualan berhasil ditambahkan!',
            'alert-type' => 'success',
        ]);
    }
    
    
    public function edit($NoFJ)
    {
        $penjualan = Penjualan::findOrFail($NoFJ);
        $penjualan->TglFJ = \Carbon\Carbon::parse($penjualan->TglFJ);

        $pesertas = Peserta::all(); 
        return view('admin.penjualan.edit', compact('penjualan', 'pesertas'));
    }
    
    public function update(Request $request, $NoFJ)
    {
        $request->validate([
            'NoFJ' => 'required|max:12',
            'TglFJ' => 'required|date',
            'id_peserta' => 'required|exists:peserta,id_peserta',
            'TotalFaktur' => 'required|numeric|min:0',
            'Pembayaran' => 'required|numeric|min:0',
            'StatusPembayaran' => 'nullable|string|max:50',
        ]);
    
        $penjualan = Penjualan::findOrFail($NoFJ);
        $penjualan->update([
            'NoFJ' => $request->NoFJ,
            'TglFJ' => $request->TglFJ,
            'id_peserta' => $request->id_peserta,
            'TotalFaktur' => $request->TotalFaktur,
            'Pembayaran' => $request->Pembayaran,
            'StatusPembayaran' => $request->StatusPembayaran,
        ]);
    
        return redirect()->route('admin.penjualan.index')->with('success', 'Faktur berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy($NoFJ)
    {
        $penjualan = Penjualan::findOrFail($NoFJ);
        $penjualan->delete();

        return redirect()->route('admin.penjualan.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Delete all selected Penjualan at once.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        Penjualan::whereIn('id', $request->ids)->delete();

        return response()->noContent();
    }


    public function laporanPenjualan(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $startDate = $validated['start'];
        $endDate = $validated['end'];

        // Mengambil data penjualan berdasarkan rentang tanggal
        $penjualans = Fjdet::whereBetween('created_at', [$startDate, $endDate])->get();

        // Debugging: Periksa apakah data penjualan ada dan formatnya benar
        // Log::info('Penjualan Data:', $penjualans->toArray());

        if ($penjualans->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data penjualan untuk rentang tanggal ini.',
                'dataPenjualan' => [],
                'totalPenjualan' => 0
            ]);
        }

        $dataPenjualan = [];

        foreach ($penjualans as $penjualan) {
            // Cek apakah NoFJ sudah ada dalam dataPenjualan, jika belum buat entry baru
            if (!isset($dataPenjualan[$penjualan->NoFJ])) {
                $dataPenjualan[$penjualan->NoFJ] = [
                    'NoFJ' => $penjualan->NoFJ,
                    'items' => []
                ];
            }

            // Menambahkan item ke dalam list items berdasarkan NoFJ
            $dataPenjualan[$penjualan->NoFJ]['items'][] = [
                'Seq' => $penjualan->Seq,
                'KodeBarang' => $penjualan->KodeBarang,
                'Qty' => $penjualan->Qty,
                'Subtotal' => $penjualan->Subtotal * $penjualan->Qty,
            ];
        }

        $totalPenjualan = 0;

        // Menghitung total penjualan
        foreach ($dataPenjualan as $penjualan) {
            foreach ($penjualan['items'] as $item) {
                $totalPenjualan += $item['Subtotal'];
            }
        }

        // Kembalikan data dalam format yang bisa diproses oleh frontend
        return response()->json([
            'dataPenjualan' => array_values($dataPenjualan), // Mengubah objek menjadi array
            'totalPenjualan' => $totalPenjualan,
        ]);
    }
    


}
