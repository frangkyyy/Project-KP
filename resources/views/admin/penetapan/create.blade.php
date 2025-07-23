@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Proses Penetapan Untuk 1 Orang</h1>
            <a href="{{ route('admin.penetapan.index') }}" class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
        </div>

        <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('Tarif Pendaftaran dan Bulanan') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>{{ __('Kelas') }}</th>
                            <th>{{ __('Nama Kelas') }}</th>
                            <th>{{ __('Pendaftaran') }}</th>
                            <th>{{ __('Bulanan') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($kelass as $kelas)
                            <tr data-entry-id="{{ $kelas->id_kelas }}">
                                <td>{{ $kelas->id_kelas }}</td>
                                <td>{{ $kelas->nama_kelas }}</td>
                                <td>{{ number_format($kelas->biaya_pendaftaran, 0, '.', ',') }}</td>
                                <td>{{ number_format($kelas->biaya_bulanan_tahunan, 0, '.', ',') }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="23" class="text-center">{{ __('Data Kosong') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


            <!-- Data Peserta -->
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.penetapan.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="carinamasiswa">{{ __('Pilih Nama Siswa') }}</label>
                            <select class="form-control" id="carinamasiswa" name="carinamasiswa" onchange="setStatusSiswa(this)">
                                @foreach($pesertas as $peserta)
                                    @if($peserta->status_siswa == 'Aktif' || $peserta->status_siswa == 'Cuti')
                                        <option value="{{ $peserta->id_peserta }}"
                                                data-address="{{ $peserta->alamat }}"
                                                data-kelas="{{ $peserta->id_kelas }}"
                                                data-status_siswa="{{ $peserta->status_siswa }}">
                                            {{ $peserta->id_peserta }} - {{ $peserta->nama }} - {{ $peserta->jenis_kelamin }} - {{ $peserta->id_kelas }} - {{ $peserta->alamat }}
                                            - {{ $peserta->kota }} - {{ $peserta->asal_sekolah }} - {{ $peserta->status_siswa }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="hidden" id="status_siswa" name="status_siswa" value="">
                        </div>

                        <div class="form-group">
                            <label for="id_belajar">{{ __('ID.Belajar/Subject') }}</label>
                            <select class="form-control" id="id_belajar" name="id_belajar">
                                @foreach($kelass as $kelas)
                                    <option value="{{ $kelas->id_kelas }}">
                                        {{ $kelas->id_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Panduan Penetapan Biaya Kursus Bulanan:</label>
                            <textarea class="form-control bg-warning text-dark" rows="4" readonly>
1. Sebelum proses penetapan, data KELAS TERAKHIR dan STATUS SISWA disesuaikan.
2. Selain itu biaya PENDAFTARAN dan biaya kursus BULANAN disesuaikan
3. Pilih ID.Belajar, Bulan dan Isi Tahun
4. Pilih Proses Penetapan Untuk 1 Orang, untuk menetapkan pembayaran siswa
                </textarea>
                        </div>

                        <div class="form-group">
                            <label for="penetapan_bulan">Penetapan Bulan</label>
                            <select name="penetapan_bulan" id="penetapan_bulan" class="form-control">
                                <option value="Januari" {{ old('penetapan_bulan') == 'Januari' ? 'selected' : '' }}>{{ __('Januari') }}</option>
                                <option value="February" {{ old('penetapan_bulan') == 'February' ? 'selected' : '' }}>{{ __('February') }}</option>
                                <option value="Maret" {{ old('penetapan_bulan') == 'Maret' ? 'selected' : '' }}>{{ __('Maret') }}</option>
                                <option value="April" {{ old('penetapan_bulan') == 'April' ? 'selected' : '' }}>{{ __('April') }}</option>
                                <option value="Mei" {{ old('penetapan_bulan') == 'Mei' ? 'selected' : '' }}>{{ __('Mei') }}</option>
                                <option value="Juni" {{ old('penetapan_bulan') == 'Juni' ? 'selected' : '' }}>{{ __('Juni') }}</option>
                                <option value="Juli" {{ old('penetapan_bulan') == 'Juli' ? 'selected' : '' }}>{{ __('Juli') }}</option>
                                <option value="Agustus" {{ old('penetapan_bulan') == 'Agustus' ? 'selected' : '' }}>{{ __('Agustus') }}</option>
                                <option value="September" {{ old('penetapan_bulan') == 'September' ? 'selected' : '' }}>{{ __('September') }}</option>
                                <option value="Oktober" {{ old('penetapan_bulan') == 'Oktober' ? 'selected' : '' }}>{{ __('Oktober') }}</option>
                                <option value="November" {{ old('penetapan_bulan') == 'November' ? 'selected' : '' }}>{{ __('November') }}</option>
                                <option value="Desember" {{ old('penetapan_bulan') == 'Desember' ? 'selected' : '' }}>{{ __('Desember') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="penetapan_tahun">{{ __('Tahun') }}</label>
                            <input type="number" name="penetapan_tahun" id="penetapan_tahun" class="form-control" value="{{ date('Y') }}" min="{{ date('Y') }}" max="2050" step="1"
                                   oninput="validateMonthAndYear()">
                        </div>

                        <div class="form-group">
                            <label for="urutan_pembayaran">Urutan Pembayaran</label>
                            <select name="urutan_pembayaran" id="urutan_pembayaran" class="form-control">
                                <option value="IDBelajar,Kelas,KodeSiswa" {{ old('urutan_pembayaran') == 'IDBelajar,Kelas,KodeSiswa' ? 'selected' : '' }}>
                                    {{ __('IDBelajar,Kelas,KodeSiswa') }}
                                </option>
                                <option value="IDBelajar,KodeSiswa,Kelas" {{ old('urutan_pembayaran') == 'IDBelajar,KodeSiswa,Kelas' ? 'selected' : '' }}>
                                    {{ __('IDBelajar,KodeSiswa,Kelas') }}
                                </option>
                                <option value="IDBelajar,JenisBayar,KodeSiswa" {{ old('urutan_pembayaran') == 'IDBelajar,JenisBayar,KodeSiswa' ? 'selected' : '' }}>
                                    {{ __('IDBelajar,JenisBayar,KodeSiswa') }}
                                </option>
                                <option value="KodeSiswa,JenisBayar" {{ old('urutan_pembayaran') == 'KodeSiswa,JenisBayar' ? 'selected' : '' }}>
                                    {{ __('KodeSiswa,JenisBayar') }}
                                </option>
                                <option value="KodeSiswa,IDBelajar" {{ old('urutan_pembayaran') == 'KodeSiswa,IDBelajar' ? 'selected' : '' }}>
                                    {{ __('KodeSiswa,IDBelajar') }}
                                </option>
                                <option value="JenisBayar,KodeSiswa,Kelas" {{ old('urutan_pembayaran') == 'JenisBayar,KodeSiswa,Kelas' ? 'selected' : '' }}>
                                    {{ __('JenisBayar,KodeSiswa,Kelas') }}
                                </option>
                                <option value="JenisBayar,Kelas,KodeSiswa" {{ old('urutan_pembayaran') == 'JenisBayar,Kelas,KodeSiswa' ? 'selected' : '' }}>
                                    {{ __('JenisBayar,Kelas,KodeSiswa') }}
                                </option>
                                <option value="JenisBayar,IDBelajar,KodeSiswa" {{ old('urutan_pembayaran') == 'JenisBayar,IDBelajar,KodeSiswa' ? 'selected' : '' }}>
                                    {{ __('JenisBayar,IDBelajar,KodeSiswa') }}
                                </option>
                                <option value="JenisBayar,Tanggal" {{ old('urutan_pembayaran') == 'JenisBayar,Tanggal' ? 'selected' : '' }}>
                                    {{ __('JenisBayar,Tanggal') }}
                                </option>
                                <option value="Siswa,Tanggal" {{ old('urutan_pembayaran') == 'Siswa,Tanggal' ? 'selected' : '' }}>
                                    {{ __('Siswa,Tanggal') }}
                                </option>
                                <!-- Tambahkan urutan lainnya jika perlu -->
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">{{ __('Proses Penetapan Untuk 1 Orang') }}</button>
                        <script>
                            document.querySelector('form').addEventListener('submit', function(event) {
                                // Ambil elemen peserta yang dipilih
                                const selectedPeserta = document.getElementById('carinamasiswa');
                                const option = selectedPeserta.options[selectedPeserta.selectedIndex]; // Dapatkan opsi yang dipilih
                                const namaPeserta = option.text; // Ambil teks lengkap dari opsi yang dipilih

                                // Ambil bulan dan tahun dari input
                                const bulan = document.getElementById('penetapan_bulan').value;
                                const tahun = document.getElementById('penetapan_tahun').value;

                                // Tampilkan konfirmasi dengan informasi lengkap
                                const confirmation = confirm(`Penetapan untuk 1 orang;\nAn: ${namaPeserta}\nBulan/Tahun: ${bulan} ${tahun}`);

                                // Jika pengguna membatalkan, hentikan pengiriman form
                                if (!confirmation) {
                                    event.preventDefault();
                                }
                            });
                        </script>

                    </form>
                </div>
            </div>
        @endsection
        <script>
            function setStatusSiswa(select) {
                const selectedOption = select.options[select.selectedIndex];
                const statusSiswa = selectedOption.getAttribute('data-status_siswa');
                document.getElementById('status_siswa').value = statusSiswa;
            }
        </script>

        <script>
            document.querySelector('select[name="carinamasiswa"]').addEventListener('change', function () {
                var selectedOption = this.options[this.selectedIndex];
                var kelas = selectedOption.getAttribute('data-kelas');

                // Isi input hidden id_kelas dengan nilai kelas peserta yang dipilih
                document.getElementById('id_kelas').value = kelas;
            });
        </script>

        <script>
            function validateMonthAndYear() {
                const currentYear = new Date().getFullYear();
                const currentMonth = new Date().getMonth() + 1; // 0-indexed
                const yearInput = document.getElementById('penetapan_tahun');
                const monthSelect = document.getElementById('penetapan_bulan');

                const selectedYear = parseInt(yearInput.value, 10);

                // Setel ulang dengan mengaktifkan semua bulan
                for (let i = 0; i < monthSelect.options.length; i++) {
                    monthSelect.options[i].disabled = false;
                }

                // Nonaktifkan bulan-bulan terakhir jika tahun yang dipilih adalah tahun berjalan
                if (selectedYear === currentYear) {
                    for (let i = 0; i < monthSelect.options.length; i++) {
                        if (i + 1 < currentMonth) {
                            monthSelect.options[i].disabled = true;
                        }
                    }
                }
            }

            // Jalankan validasi saat memuat halaman
            document.addEventListener('DOMContentLoaded', validateMonthAndYear);

            // Jalankan kembali validasi ketika tahun atau bulan berganti
            document.getElementById('penetapan_tahun').addEventListener('input', validateMonthAndYear);
            document.getElementById('penetapan_bulan').addEventListener('change', validateMonthAndYear);
        </script>
    </div>

