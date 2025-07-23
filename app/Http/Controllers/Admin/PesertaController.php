<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelass = Kelas::all();
        $pesertas = Peserta::with('kelas')->get();
        return view('admin.peserta.index', compact('kelass','pesertas'));
    }


    public function getKodeSiswa(Request $request)
    {
//        $pelajaran = $request->pelajaran;
//
//        if (!$pelajaran) {
//            return response()->json(['kode_siswa' => null]);
//        }
//
//        $prefix = substr($pelajaran, 0, 1); // Ambil huruf pertama pelajaran
//        $lastKode = DB::table('peserta')
//            ->where('kodesiswa', 'like', "$prefix%")
//            ->orderBy('kodesiswa', 'desc')
//            ->value('kodesiswa');
//
//        $newNumber = 1; // Default jika belum ada data
//        if ($lastKode) {
//            $lastNumber = (int) substr($lastKode, 2); // Ambil angka terakhir
//            $newNumber = $lastNumber + 1;
//        }
//
//        $newKode = $prefix . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
//        return response()->json(['kode_siswa' => $newKode]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.peserta.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createkelas()
    {
        return view('admin.peserta.createkelas');
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
            'id_peserta' => 'required|unique:peserta,id_peserta',
            'nama' => 'required|string|max:45',
            'nama_panggilan' => 'required|string|max:45',
            'gender' => 'required|string|in:Laki-Laki,Perempuan',
            'haribljr' => 'required|string|max:45',
            'mulaibljr' => 'nullable|date',
            'lamabljr' => 'required|integer|min:1',
            'kelas' => 'required|string|max:5',
            'tmpt_lahir' => 'required|string|max:45',
            'tgl_lahir' => 'required|date',
            'kelastrakhir' => 'required|string|max:5',
            'asal_sekolah' => 'required|string|max:45',
            'alamat' => 'required|string|max:45',
            'kota' => 'required|string|max:45',
            'kodepos' => 'required|digits:5',
            'email' => 'required|email|max:45',
            'telp' => 'required|string|max:12',
//            'telpayah' => 'nullable|string|max:12',
//            'telpibu' => 'nullable|string|max:12',
            'pndkan' => 'required|string|max:45',
            'status' => 'required|string|in:Aktif,Cuti,Berhenti,Lulus',
            'tgl_daftar' => date('Y-m-d')
            //'tgllulus_berhenti' => 'required|date',
        ]);

        // Menyimpan data peserta ke dalam tabel
        $peserta = Peserta::create([
        'id_peserta' => $request->id_peserta,
        'nama' => $request->nama,
        'id_kelas' => $request->kelas,
        'nama_panggilan' => $request->nama_panggilan,
        'jenis_kelamin' => $request->gender,
        'tmpt_lahir' => $request->tmpt_lahir,
        'tgl_lahir' => $request->tgl_lahir,
        'asal_sekolah' => $request->asal_sekolah,
        'alamat' => $request->alamat,
        'kota' => $request->kota,
        'kode_pos' => $request->kodepos,
        'email' => $request->email,
        'nohp_ortu_wali' => $request->telp,
//        $peserta->telpayah => $request['nohp_ortu_wali'];
//        $peserta->telpibu => $request['nohp_ortu_wali'];
        'tingkat_terakhir_pendidikan' => $request->pndkan,
        'kelas_terakhir' => $request->kelastrakhir,
        'jadwal_belajar' => $request->haribljr,
        'lama_belajar' => $request->lamabljr,
        'tgl_mulai_belajar' => $request->mulaibljr,
        'status_siswa' => $request->status,
        //'tgl_lulusberhenti' => $request->tgllulus_berhenti = $validated['tgllulus_berhenti'];

        // Menyimpan data ke database
        ]);

        // Menampilkan data yang berhasil disimpan
        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta berhasil ditambahkan.')
            ->with('peserta', $peserta);
    }

    public function storetambahkelas(Request $request)
    {
        // Validasi input form
        $request->validate([
            'id_peserta' => 'required|unique:peserta,id_peserta',
            'nama' => 'required|string|max:45',
            'nama_panggilan' => 'required|string|max:45',
            'gender' => 'required|string|in:Laki-Laki,Perempuan',
            'haribljr' => 'required|string|max:45',
            'mulaibljr' => 'nullable|date',
            'lamabljr' => 'required|integer|min:1',
            'kelas' => 'required|string|max:5',
            'tmpt_lahir' => 'required|string|max:45',
            'tgl_lahir' => 'required|date',
            'kelastrakhir' => 'required|string|max:5',
            'asal_sekolah' => 'required|string|max:45',
            'alamat' => 'required|string|max:45',
            'kota' => 'required|string|max:45',
            'kodepos' => 'required|digits:5',
            'email' => 'required|email|max:45',
            'telp' => 'required|string|max:12',
//            'telpayah' => 'nullable|string|max:12',
//            'telpibu' => 'nullable|string|max:12',
            'pndkan' => 'required|string|max:45',
            'status' => 'required|string|in:Aktif,Cuti,Berhenti,Lulus',
            'tgl_daftar' => date('Y-m-d')
            //'tgllulus_berhenti' => 'required|date',
        ]);

        // Menyimpan data peserta ke dalam tabel
        $peserta = Peserta::create([
            'id_peserta' => $request->id_peserta,
            'nama' => $request->nama,
            'id_kelas' => $request->kelas,
            'nama_panggilan' => $request->nama_panggilan,
            'jenis_kelamin' => $request->gender,
            'tmpt_lahir' => $request->tmpt_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'asal_sekolah' => $request->asal_sekolah,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'kode_pos' => $request->kodepos,
            'email' => $request->email,
            'nohp_ortu_wali' => $request->telp,
//        $peserta->telpayah => $request['nohp_ortu_wali'];
//        $peserta->telpibu => $request['nohp_ortu_wali'];
            'tingkat_terakhir_pendidikan' => $request->pndkan,
            'kelas_terakhir' => $request->kelastrakhir,
            'jadwal_belajar' => $request->haribljr,
            'lama_belajar' => $request->lamabljr,
            'tgl_mulai_belajar' => $request->mulaibljr,
            'status_siswa' => $request->status,
            //'tgl_lulusberhenti' => $request->tgllulus_berhenti = $validated['tgllulus_berhenti'];

            // Menyimpan data ke database
        ]);

        // Menampilkan data yang berhasil disimpan
        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta berhasil ditambahkan.')
            ->with('peserta', $peserta);
    }

    public function getLastId(Request $request)
    {
        $kelas = $request->get('kelas');

        if (!$kelas) {
            return response()->json(['error' => 'Kelas tidak ditemukan'], 400);
        }

        // Cari peserta terakhir berdasarkan kelas
        $lastPeserta = Peserta::where('kelas', $kelas)
            ->orderBy('id_peserta', 'desc')
            ->first();

        $prefixMap = [
            'CV' => 'C',
            'EL' => 'E',
            'HSK' => 'H',
            'IV' => 'I',
            'KD' => 'K',
            'PV' => 'P',
            'TA' => 'T',
            'REG' => 'R',
            '1A' => 'A',
        ];

        $prefix = $prefixMap[$kelas] ?? '';

        if ($lastPeserta) {
            // Ambil angka dari ID peserta terakhir
            $lastNumber = (int)substr($lastPeserta->id_peserta, 2);
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }

        $newId = $prefix . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return response()->json(['new_id' => $newId]);
    }

    public function showForm()
    {
        $kelas = Kelas::all(); // Misal, ambil data kelas
        $lastPeserta = Peserta::orderBy('id_peserta', 'desc')->first(); // Ambil ID terakhir
        $lastId = $lastPeserta ? $lastPeserta->id_peserta : 'CV-00000';

        return view('form', compact('kelas', 'lastId'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\Http\Response
     */
    public function show(Peserta $peserta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\Http\Response
     */
    public function edit($id_peserta)
    {
        $peserta = Peserta::findOrFail($id_peserta);  // Ambil data mata pelajaran berdasarkan ID
        return view('admin.peserta.edit', compact('peserta'));  // Kirim data ke view
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\Http\Response
     */
    public function editBiaya($id_kelas)
    {
        $kelas = Kelas::where('id_kelas', $id_kelas)->first();

        if (!$kelas) {
            abort(404, 'Kelas tidak ditemukan');
        }

        return view('admin.peserta.editbiaya', compact('kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_peserta)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:45',
            'panggilan' => 'nullable|string|max:45',
            'tmpt_lahir' => 'required|string|max:45',
            'tgl_lahir' => 'required|date',
            'sekolah' => 'required|string|max:45',
            'kelas' => 'required|string|max:5',
            'alamat' => 'required|string|max:45',
            'kota' => 'required|string|max:45',
            'kodepos' => 'required|digits:5',
            'email' => 'required|email|max:45',
            'telp' => 'required|string|max:12',
            'gender' => 'required|in:Laki-Laki,Perempuan',
            'pndkan' => 'required   |string|max:45',
            'kelastrakhir' => 'nullable|string|max:5',
            'haribljr' => 'required|string|max:45',
            'status' => 'required|in:Aktif,Cuti,Berhenti,Lulus',
        ]);

        // Cari peserta berdasarkan ID
        $peserta = Peserta::findOrFail($id_peserta);

        // Update data peserta
        $peserta->update([
            'nama' => $request->input('nama'),
            'nama_panggilan' => $request->input('panggilan'),
            'tmpt_lahir' => $request->input('tmpt_lahir'),
            'tgl_lahir' => $request->input('tgl_lahir'),
            'asal_sekolah' => $request->input('sekolah'),
            'id_kelas' => $request->input('kelas'),
            'alamat' => $request->input('alamat'),
            'kota' => $request->input('kota'),
            'kode_pos' => $request->input('kodepos'),
            'email' => $request->input('email'),
            'nohp_ortu_wali' => $request->input('telp'),
            'jenis_kelamin' => $request->input('gender'),
            'tingkat_terakhir_pendidikan' => $request->input('pndkan'),
            'kelas_terakhir' => $request->input('kelastrakhir'),
            'jadwal_belajar' => $request->input('haribljr'),
            'status_siswa' => $request->input('status'),
        ]);

        // Redirect kembali ke halaman daftar peserta dengan pesan sukses
        return redirect()->route('admin.peserta.index')->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\Http\Response
     */
    public function updateBiaya(Request $request, $id_kelas)
    {
        $request->validate([
            'biaya_pendaftaran' => 'required|numeric',
            'biaya_bulanan_tahunan' => 'required|numeric',
        ]);

        $kelas = Kelas::where('id_kelas', $id_kelas)->first();

        if (!$kelas) {
            abort(404, 'Kelas tidak ditemukan');
        }

        $kelas->update([
            'biaya_pendaftaran' => $request->biaya_pendaftaran,
            'biaya_bulanan_tahunan' => $request->biaya_bulanan_tahunan,
        ]);

        return redirect()->route('admin.peserta.index')->with('success', 'Biaya berhasil diperbarui');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_peserta)
    {
        $peserta = Peserta::findOrFail($id_peserta);
        $peserta->delete();

        return redirect()->route('admin.peserta.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Delete all selected Permission at once.
     *
     * @param \Illuminate\Support\Facades\Request $request
     */
    public function massDestroy(Request $request)
    {
        $ids = $request->ids;

        // Hapus peserta berdasarkan ID yang dikirim
        Peserta::whereIn('id_peserta', $ids)->delete();

        return response()->noContent();
    }


}

