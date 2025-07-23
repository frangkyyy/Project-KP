<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenetapanPembayaran;
use App\Models\Peserta;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Menambahkan import DB

class PenetapanPembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('peserta')) {
            $pesertas = PenetapanPembayaran::where('id_peserta', $request->input('peserta'))->get();
        }

        $kelass = Kelas::all();
        $pembayaranss = PenetapanPembayaran::all();
        $pesertas = Peserta::all(); // Ambil semua data Peserta
//        $user = Auth::user(); // Ambil data user yang sedang login
//        $roles = $user->roles; // Ambil role user
        return view('admin.penetapan.index', compact('pembayaranss' , 'pesertas', 'kelass'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexpembayaran(Request $request)
    {
        $pembayaranss = PenetapanPembayaran::all();
        $kelass = Kelas::all();
        $pesertas = Peserta::all();

        // Cek apakah ada peserta yang dipilih
        $selectedPesertaId = $request->carinama;

        if ($selectedPesertaId) {
            $pesertas = Peserta::where('id_peserta', $selectedPesertaId)->get();
        }

        foreach ($pesertas as $peserta) {
            // Ambil semua bulan dan tahun terlama dengan kwitansi kosong
            $penetapanList = PenetapanPembayaran::where('id_peserta', $peserta->id_peserta)
                ->where(function ($query) {
                    $query->whereNull('id_penetapan')
                        ->orWhere('id_penetapan', '');
                })
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->get();

            if ($penetapanList->isNotEmpty()) {
                foreach ($penetapanList as $penetapan) {
                    // Cek apakah ada baris lain dengan bulan & tahun yang sama
                    $duplicateExists = PenetapanPembayaran::where('id_peserta', $peserta->id_peserta)
                            ->where('bulan', $penetapan->bulan)
                            ->where('tahun', $penetapan->tahun)
                            ->count() > 1;

                    if (!$duplicateExists) {
                        $peserta->bulan_tanpa_duplikat = $penetapan->bulan;
                        $peserta->tahun_tanpa_duplikat = $penetapan->tahun;
                        break; // Ambil hanya data pertama yang memenuhi syarat
                    }
                }
            } else {
                $peserta->bulan_tanpa_duplikat = '';
                $peserta->tahun_tanpa_duplikat = '';
            }

            // Ambil data biaya dari kelas
            $kelas = Kelas::find($peserta->id_kelas);
            $peserta->biaya_bulanan_tahunan = $kelas ? $kelas->biaya_bulanan_tahunan : 0;
            $peserta->biaya_pendaftaran = $kelas ? $kelas->biaya_pendaftaran : 0;
        }

        return view('admin.pembayaran.index', compact('pembayaranss', 'pesertas', 'kelass'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pembayaranss = PenetapanPembayaran::all();
        $kelass = Kelas::all();
        $pesertas = Peserta::all(); // Ambil semua data Peserta
        return view('admin.penetapan.create', compact('kelass' , 'pesertas', 'pembayaranss'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'carinamasiswa' => 'required|exists:peserta,id_peserta',
            'status_siswa' => 'required|string|max:10',
//            'id_kelas' => 'required|string|max:5',
            'id_belajar' => 'required|string|max:5',
            'penetapan_bulan' => 'required|string|max:45',
            'penetapan_tahun' => 'required|string|max:45',
            'urutan_pembayaran' => 'required|string|max:45',
        ]);

        // Ambil data kelas berdasarkan ID Peserta yang dipilih
        $kelas = Kelas::where('id_kelas', $request->id_belajar)->first();

        if (!$kelas) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }
        $pembayaran = PenetapanPembayaran::create([
            'id_pembayaran' => rand(10000, 99999),
            'id_peserta' => $request->carinamasiswa,
            'id_kelas' => $request->id_belajar,
            'tanggal_transaksi' => date('Y-m-d'),
            'jumlah_bayar' => $kelas->biaya_bulanan_tahunan,
            'debet_kredit' => 'DB',
            'jenis_pembayaran' => '',
            'sistem_pembayaran' => '',
            'id_penetapan' => '',
            'bulan' => $request->penetapan_bulan,
            'tahun' => $request->penetapan_tahun,
            'keterangan' => 'Bi.Kursus Bln:' . $request->penetapan_bulan . ' ' . $request->penetapan_tahun,
            'status_siswa' => $request->status_siswa,
        ]);

        // Simpan data pembayaran ke dalam session untuk ditampilkan
        session(['pembayaran' => $pembayaran]);

        // Redirect ke halaman index dengan data yang baru disimpan
        return redirect()->route('admin.penetapan.index')->with('success', 'Data berhasil disimpan!');

        \Log::info(request()->all());
    }

    //BUAT PEMBAYARAN
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePembayaran(Request $request)
    {
        // Validasi input form
        $request->validate([
            'no_kwitansi' => 'required|string',
            'carinama' => 'required|exists:peserta,id_peserta',
            'alamat' => 'required|string',
            'kelas' => 'required|string',
            'status_siswa' => 'nullable|string',
            'registrasi' => 'nullable|numeric|min:0',
            'biayakursus' => 'required|numeric|min:0',
            'diskonkursus' => 'nullable|numeric|min:0',
            'totalpembayaran' => 'required|numeric|min:0',
            'jenispembayaran' => 'required|string',
            'ket' => 'nullable|string',
        ]);

        $idPeserta = $request->carinama;

        // Ambil semua bulan & tahun dengan kwitansi kosong
        $penetapanList = PenetapanPembayaran::where('id_peserta', $idPeserta)
            ->where(function ($query) {
                $query->whereNull('id_penetapan')
                    ->orWhere('id_penetapan', '');
            })
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        if ($penetapanList->isEmpty()) {
            return redirect()->route('admin.penetapan.index')->with('error', 'Tidak ada pembayaran tertunda untuk peserta ini.');
        }

        $bulan = '';
        $tahun = '';
        foreach ($penetapanList as $penetapan) {
            $duplicateExists = PenetapanPembayaran::where('id_peserta', $idPeserta)
                    ->where('bulan', $penetapan->bulan)
                    ->where('tahun', $penetapan->tahun)
                    ->count() > 1;

            if (!$duplicateExists) {
                $bulan = $penetapan->bulan;
                $tahun = $penetapan->tahun;
                break;
            }
        }

        if (empty($bulan) || empty($tahun)) {
            return redirect()->route('admin.penetapan.index')->with('error', 'Tidak ada bulan dan tahun yang valid untuk pembayaran.');
        }

        // Ambil data kelas
        $kelas = Kelas::where('id_kelas', $request->kelas)->first();
        if (!$kelas) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $registrasiAmount = $kelas->biaya_pendaftaran ?? 0;
        $biayakursusAmount = $kelas->biaya_bulanan_tahunan ?? 0;

        // Pastikan biaya sesuai dengan data di kelas
        $registrasi = $request->has('registrasi') ? $registrasiAmount : 0;
        $biayakursus = $request->has('biayakursus') ? $biayakursusAmount : 0;

        $totalpembayaran = $registrasi + $biayakursus - ($request->diskonkursus ?? 0);

        // Ambil jenis pembayaran dari request form (sesuai pilihan user)
        $jenisPembayaran = $request->jenispembayaran;

        // Pilihan jenis pembayaran
        $jenisPembayaranOptions = [
            'Trf BCA',
            'Trf Mandiri',
            'Deb BCA',
            'Deb BNI',
            'Deb Mandiri',
            'KKredit',
        ];

        // Pilih jenis pembayaran secara random
        $randomJenisPembayaran = $jenisPembayaranOptions[array_rand($jenisPembayaranOptions)];

        // Tentukan sistem pembayaran berdasarkan jenis pembayaran menggunakan switch-case
        $sistemPembayaran = '';

        switch ($randomJenisPembayaran) {
            case 'Trf BCA':
            case 'Trf Mandiri':
                $sistemPembayaran = 'Transfer';
                break;

            case 'Deb BCA':
            case 'Deb BNI':
            case 'Deb Mandiri':
                $sistemPembayaran = 'Debet';
                break;

            case 'KKredit':
                $sistemPembayaran = 'Kredit';
                break;

            default:
                $sistemPembayaran = 'Tidak Diketahui';
                break;
        }

        // Simpan data pembayaran ke database
        $pembayaran = PenetapanPembayaran::create([
            'id_pembayaran' => rand(10000, 99999),
            'id_peserta' => $idPeserta,
            'id_kelas' => $request->kelas,
            'status_siswa' => $request->status_siswa,
            'tanggal_transaksi' => date('Y-m-d'),
            'jumlah_bayar' => $totalpembayaran,
            'debet_kredit' => 'KR',
            'jenis_pembayaran' => $jenisPembayaran,
            'sistem_pembayaran' => $sistemPembayaran,
            'id_penetapan' => $request->no_kwitansi,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'keterangan' => 'Byr.Kursus ' . $bulan . ' ' . $tahun . ' ' . $request->ket,
        ]);

        // Simpan data pembayaran ke dalam session untuk ditampilkan
        session(['pembayaran' => $pembayaran]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.penetapan.index')->with('success', 'Data Pembayaran berhasil disimpan!')->with('pembayaran', $pembayaran);
    }



    public function getPenetapanByPeserta(Request $request)
    {
        // Ambil ID Peserta yang dipilih
        $idPeserta = $request->id_peserta;

        // Ambil data pembayaran berdasarkan ID Peserta
        $pembayarans = PenetapanPembayaran::where('id_peserta', $idPeserta)->get();

        // Kembalikan data sebagai response JSON
        return response()->json(['data' => $pembayarans]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PenetapanPembayaran  $penetapanPembayaran
     * @return \Illuminate\Http\Response
     */
    public function show(PenetapanPembayaran $penetapanPembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PenetapanPembayaran  $penetapanPembayaran
     * @return \Illuminate\Http\Response
     */
    public function edit(PenetapanPembayaran $penetapanPembayaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PenetapanPembayaran  $penetapanPembayaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PenetapanPembayaran $penetapanPembayaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PenetapanPembayaran  $penetapanPembayaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenetapanPembayaran $penetapanPembayaran)
    {
        //
    }
}
