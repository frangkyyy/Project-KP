    @extends('layouts.admin')

    @section('content')
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Data Peserta dan Data Kelas</h1>
                <a href="{{ route('admin.penetapan.create') }}" class="btn btn-success ml-auto">
            <span class="icon text-white-50">
                <i class="fa fa-plus"></i>
            </span>
                    <span class="text">{{ __('Buat Proses Penetapan Untuk 1 Orang') }}</span>
                </a>
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
                                <th class="text-end">{{ __('Pendaftaran') }}</th>
                                <th class="text-end">{{ __('Bulanan') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($kelass as $kelas)
                                <tr data-entry-id="{{ $kelas->id_kelas }}">
                                    <td>{{ $kelas->id_kelas }}</td>
                                    <td>{{ $kelas->nama_kelas }}</td>
                                    <td style="text-align: right;" class="text-end">Rp. {{ number_format($kelas->biaya_pendaftaran, 0, '.', ',') }}</td>
                                    <td style="text-align: right;" class="text-end">Rp. {{ number_format($kelas->biaya_bulanan_tahunan, 0, '.', ',') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">{{ __('Data Kosong') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--Data Penetapan dan Pembayaran Kursus -->
            <div class="card">
                <div class="card-header py-3 d-flex">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ __('Data Penetapan dan Pembayaran Kursus') }}
                    </h6>
                </div>

                <div class="card-header py-3 d-flex">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ __('Laporan Pembayaran Yang Sudah Membayar') }}
                    </h6>
                </div>

                <div class="card-body">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="dari_tanggal">{{ __('Dari Tanggal') }}</label>
                                <input type="date" id="dari_tanggal" class="form-control" name="dari_tanggal">
{{--                                <button id="laporanBelumBayarBtn" class="btn btn-primary mt-3" style="vertical-align: middle;">--}}
{{--                                    {{ __('Laporan Belum Bayar') }}--}}
{{--                                </button>--}}
                            </div>
                            <div class="col-md-3">
                                <label for="sampai_tanggal">{{ __('Sampai Dengan') }}</label>
                                <input type="date" id="sampai_tanggal" class="form-control" name="sampai_tanggal">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <!-- Tombol Tampilkan -->
                                <button type="submit" class="btn btn-primary mr-2">
                                    {{ __('Tampilkan') }}
                                </button>
                                <!-- Tombol Cetak -->
                                <button id="printPdfBtn" class="btn btn-primary mr-2">
                                    {{ __('Cetak') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-header py-3 d-flex">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ __('Laporan Pembayaran Yang Belum Membayar') }}
                    </h6>
                </div>

                <div class="card-body">
                    <form id="filterBelumBayarForm">
                        <div class="row">
{{--                            <div class="col-md-3">--}}
{{--                                <label for="dari_tanggal_belum">{{ __('Dari Tanggal') }}</label>--}}
{{--                                <input type="date" id="dari_tanggal_belum" class="form-control" name="dari_tanggal_belum">--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3">--}}
{{--                                <label for="sampai_tanggal_belum">{{ __('Sampai Dengan') }}</label>--}}
{{--                                <input type="date" id="sampai_tanggal_belum" class="form-control" name="sampai_tanggal_belum">--}}
{{--                            </div>--}}
                            <div class="col-md-6 d-flex align-items-end">
                                <!-- Tombol Tampilkan -->
                                <button type="submit" class="btn btn-primary mr-2">
                                    {{ __('Tampilkan') }}
                                </button>
                                <!-- Tombol Cetak -->
                                <button id="printPdfBtnBelumMembayar" class="btn btn-primary mr-2">
                                    {{ __('Cetak') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                        <div class="form-group">
                            <label for="searchInput">{{ __('Cari Nama Siswa') }}</label>
                            <input type="text" id="searchInput" class="form-control" placeholder="Ketik nama siswa">
                        </div>
                        <!-- Dropdown untuk memilih Peserta -->
                        <div class="form-group">
                            <label for="peserta">{{ __('Pilih Nama Siswa') }}</label>
                            <select id="peserta" class="form-control" name="peserta">
                                <option value="all">{{ __('Semua Siswa') }}</option>
                                @foreach($pesertas as $peserta)
                                    <option value="{{ $peserta->id_peserta }}">{{ $peserta->id_peserta }} ( {{ $peserta->nama }} )</option>
                                @endforeach
                            </select>
                        </div>

{{--                    <div class="card-body">--}}
                    <div class="table-responsive">
                        <table id="table-pb" class="table table-bordered table-striped table-hover datatable datatable-User" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>{{ __('ID Peserta') }}</th>
                                <th>{{ __('No Bukti') }}</th>
                                <th>{{ __('Kwitansi') }}</th>
                                <th>{{ __('ID Belajar') }}</th>
                                <th>{{ __('Tanggal') }}</th>
                                <th>{{ __('Kelas') }}</th>
                                <th>{{ __('Tahun') }}</th>
                                <th>{{ __('Bulan') }}</th>
                                <th>{{ __('Keterangan') }}</th>
                                <th>{{ __('Kode') }}</th>
                                <th>{{ __('Jumlah') }}</th>
                                <th>{{ __('Status Siswa') }}</th>
                                <th>{{ __('Jenis Bayar') }}</th>
                            </tr>
                            </thead>
                            <tbody id="table-body">
                            @forelse($pembayaranss as $pembayaran)
                                <tr data-peserta="{{ $pembayaran->id_peserta }}" data-tanggal="{{ $pembayaran->tanggal_transaksi }}">
                                <td>{{ $pembayaran->id_peserta }}</td>
                                <td>{{ $pembayaran->id_pembayaran}}</td> <!-- Mengambil data kelas dari peserta -->
                                <td>{{ $pembayaran->id_penetapan}}</td>
                                <td>{{ $pembayaran->id_kelas}}</td>
                                <td>{{ $pembayaran->tanggal_transaksi}}</td>
                                <td>{{ $pembayaran->id_kelas}}</td>
                                <td>{{ $pembayaran->tahun}}</td>
                                <td>{{ $pembayaran->bulan}}</td>
                                <td>{{ $pembayaran->keterangan}}</td>
                                <td>{{ $pembayaran->debet_kredit}}</td>
                                <td>{{ $pembayaran->jumlah_bayar}}</td>
                                <td>{{ $pembayaran->status_siswa}}</td>
                                <td>{{ $pembayaran->jenis_pembayaran}}</td>
                                </tr>
                            @empty
{{--                                <tr>--}}
{{--                                    <td colspan="23" class="text-center">{{ __('Data Kosong') }}</td>--}}
{{--                                </tr>--}}
                            @endforelse

                            <!-- Menampilkan data terbaru jika ada -->
                            @if(session('pembayaran'))
                                <tr>
                                    <td>{{ session('pembayaran')->id_peserta }}</td>
                                    <td>{{ session('pembayaran')->id_pembayaran }}</td>
                                    <td>{{ session('pembayaran')->id_penetapan }}</td>
                                    <td>{{ session('pembayaran')->id_kelas }}</td>
                                    <td>{{ session('pembayaran')->tanggal_transaksi }}</td>
                                    <td>{{ session('pembayaran')->id_kelas }}</td>
                                    <td>{{ session('pembayaran')->tahun }}</td>
                                    <td>{{ session('pembayaran')->bulan }}</td>
                                    <td>{{ session('pembayaran')->keterangan }}</td>
                                    <td>{{ session('pembayaran')->debet_kredit }}</td>
                                    <td>{{ session('pembayaran')->jumlah_bayar }}</td>
                                    <td>{{ session('pembayaran')->status_siswa }}</td>
                                    <td>{{ session('pembayaran')->jenis_pembayaran }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
            </div>

        </div>
    @endsection
    @push('script-alt')
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const searchInput = document.getElementById('searchInput');
                        const pesertaSelect = document.getElementById('peserta');
                        const tableRows = document.querySelectorAll('#table-body tr');
                        const filterForm = document.getElementById('filterForm');
                        const pesertaData = {
                            @foreach ($pesertas as $peserta)
                            "{{ $peserta->id_peserta }}": "{{ strtolower($peserta->nama) }}", // Nama peserta dalam huruf kecil
                            @endforeach
                        };

                        searchInput.addEventListener('input', function () {
                            const searchTerm = this.value.toLowerCase(); // Ambil teks pencarian (huruf kecil)

                            tableRows.forEach(row => {
                                const idPeserta = row.querySelector('td:nth-child(1)')?.textContent.trim(); // Ambil ID Peserta
                                const namaPeserta = pesertaData[idPeserta] || ''; // Nama peserta sesuai ID

                                // Tampilkan baris jika nama peserta mengandung teks pencarian
                                if (namaPeserta.includes(searchTerm)) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                        });


                        document.getElementById('filterBelumBayarForm').addEventListener('submit', function(event) {
                            event.preventDefault();

                            const tableRows = document.querySelectorAll('#table-body tr');
                            let pesertaBulanTahunCount = new Map(); // Menyimpan jumlah kemunculan bulan-tahun per peserta
                            let pesertaMap = new Map(); // Menyimpan peserta yang lolos filter

                            // **Langkah 1: Hitung jumlah kemunculan bulan-tahun per peserta**
                            tableRows.forEach(row => {
                                const pesertaId = row.querySelector('td:nth-child(1)').textContent.trim(); // ID Peserta
                                const bulan = row.querySelector('td:nth-child(8)').textContent.trim(); // Bulan
                                const tahun = row.querySelector('td:nth-child(7)').textContent.trim(); // Tahun
                                const keyPesertaBulanTahun = `${pesertaId}-${bulan}-${tahun}`;

                                // Tambahkan jumlah kemunculan bulan-tahun hanya untuk peserta yang sama
                                if (!pesertaBulanTahunCount.has(pesertaId)) {
                                    pesertaBulanTahunCount.set(pesertaId, new Map());
                                }
                                let bulanTahunMap = pesertaBulanTahunCount.get(pesertaId);
                                bulanTahunMap.set(keyPesertaBulanTahun, (bulanTahunMap.get(keyPesertaBulanTahun) || 0) + 1);
                            });

                            // **Langkah 2: Tandai peserta yang tidak memiliki duplikasi bulan-tahun**
                            tableRows.forEach(row => {
                                const pesertaId = row.querySelector('td:nth-child(1)').textContent.trim();
                                const bulan = row.querySelector('td:nth-child(8)').textContent.trim();
                                const tahun = row.querySelector('td:nth-child(7)').textContent.trim();
                                const kwitansi = row.querySelector('td:nth-child(3)').textContent.trim();
                                const keyPesertaBulanTahun = `${pesertaId}-${bulan}-${tahun}`;

                                let bulanTahunMap = pesertaBulanTahunCount.get(pesertaId);

                                // **Cek kondisi untuk menampilkan data:**
                                // 1. Kwitansi kosong ("")
                                // 2. Tidak ada duplikasi bulan-tahun untuk peserta yang sama
                                if (kwitansi === "" && bulanTahunMap.get(keyPesertaBulanTahun) === 1) {
                                    pesertaMap.set(keyPesertaBulanTahun, row);
                                }
                            });

                            // **Langkah 3: Tampilkan data peserta yang lolos filter**
                            tableRows.forEach(row => {
                                const pesertaId = row.querySelector('td:nth-child(1)').textContent.trim();
                                const bulan = row.querySelector('td:nth-child(8)').textContent.trim();
                                const tahun = row.querySelector('td:nth-child(7)').textContent.trim();
                                const keyPesertaBulanTahun = `${pesertaId}-${bulan}-${tahun}`;

                                if (pesertaMap.has(keyPesertaBulanTahun)) {
                                    row.style.display = ''; // Tampilkan
                                } else {
                                    row.style.display = 'none'; // Sembunyikan
                                }
                            });
                        });

                        // Fungsi untuk memformat tanggal menjadi YYYY-MM-DD (menghapus waktu)
                        function formatDate(dateString) {
                            const dateObj = new Date(dateString);
                            const year = dateObj.getFullYear();
                            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                            const day = String(dateObj.getDate()).padStart(2, '0');
                            return `${year}-${month}-${day}`; // Mengembalikan format YYYY-MM-DD
                        }


                        // Filter berdasarkan peserta
                        pesertaSelect.addEventListener('change', function () {
                            const selectedPeserta = this.value;
                            tableRows.forEach(row => {
                                if (selectedPeserta === "all" || row.dataset.peserta === selectedPeserta) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                        });

                        // Filter data berdasarkan tanggal dan keterangan "Byr.Kursus"
                        document.getElementById('filterForm').addEventListener('submit', function (event) {
                            event.preventDefault();

                            const dariTanggal = document.getElementById('dari_tanggal').value;
                            const sampaiTanggal = document.getElementById('sampai_tanggal').value;

                            tableRows.forEach(row => {
                                const tanggal = row.querySelector('td:nth-child(5)').textContent.trim(); // Kolom Tanggal
                                const keterangan = row.querySelector('td:nth-child(9)').textContent.trim(); // Kolom Keterangan

                                // Memformat tanggal
                                const formattedTanggal = formatDate(tanggal);

                                // Menampilkan baris hanya jika tanggal sesuai dengan rentang dan keterangan mengandung "Byr.Kursus"
                                if (formattedTanggal >= dariTanggal && formattedTanggal <= sampaiTanggal && keterangan.includes('Byr.Kursus')) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                        });

                        // Fungsi untuk format tanggal (YYYY-MM-DD)
                        function formatDateToYMD(dateObj) {
                            const year = dateObj.getFullYear();
                            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                            const day = String(dateObj.getDate()).padStart(2, '0');
                            return `${year}-${month}-${day}`; // Mengembalikan format YYYY-MM-DD
                        }

                        // Fungsi untuk mencetak PDF
                        document.getElementById('printPdfBtn').addEventListener('click', function () {
                            const { jsPDF } = window.jspdf;
                            const doc = new jsPDF('landscape', 'mm', 'a4'); // Mengatur orientasi landscape

                            let y = 20; // Posisi Y untuk baris pertama
                            const pageHeight = doc.internal.pageSize.height; // Tinggi halaman PDF
                            const lineSpacing = 10; // Spasi antar baris

                            // Ambil nilai dari form filter (Dari Tanggal dan Sampai Tanggal)
                            const dariTanggal = document.getElementById('dari_tanggal').value;
                            const sampaiTanggal = document.getElementById('sampai_tanggal').value;
                            const periode = `Periode: ${dariTanggal} s/d ${sampaiTanggal}`;

                            // Menambahkan judul PDF
                            doc.setFontSize(18);
                            doc.text('Laporan Harian Pembayaran Kursus', 14, y);
                            y += 10;

                            // Menambahkan Periode di bawah judul
                            doc.setFontSize(12);
                            doc.setFont("helvetica", "normal");
                            doc.text(periode, 14, y);  // Menampilkan Periode
                            y += 10; // Pindahkan sedikit agar tidak terlalu dekat dengan header tabel

                            // Menambahkan header tabel dengan kolom yang dipilih
                            doc.setFontSize(12);
                            doc.setFont("helvetica", "bold");
                            doc.text('No', 14, y);
                            doc.text('Tanggal', 20, y);
                            doc.text('ID Peserta', 50, y);
                            doc.text('Nama Peserta', 75, y); // Kolom Nama Peserta
                            doc.text('Keterangan', 113, y);
                            doc.text('Status Siswa', 185, y);
                            doc.text('Kelas', 215, y);
                            doc.text('Jumlah', 230, y);
                            doc.text('Jenis Bayar', 255, y);

                            y += 5;

                            // Garis horizontal di bawah header tabel
                            doc.line(14, y, 287, y); // Panjang garis horizontal sesuai dengan tabel
                            y += 5;

                            // Data peserta (ID Peserta -> Nama Peserta)
                            const pesertaData = {
                                @foreach ($pesertas as $peserta)
                                "{{ $peserta->id_peserta }}": "{{ $peserta->nama }}",
                                @endforeach
                            };

                            // Konversi tabel ke array dan urutkan berdasarkan nama peserta
                            const sortedRows = Array.from(tableRows)
                                .filter(row => row.style.display !== 'none') // Hanya baris yang terlihat
                                .map(row => {
                                    const columns = Array.from(row.cells).map(cell => cell.textContent.trim());
                                    const idPeserta = columns[0]; // ID Peserta
                                    const namaPeserta = pesertaData[idPeserta] || '-'; // Nama Peserta
                                    return {
                                        no: row.rowIndex, // Indeks asli dari tabel
                                        tanggal: new Date(columns[4]), // Konversi tanggal untuk pengurutan
                                        idPeserta: idPeserta,
                                        namaPeserta: namaPeserta,
                                        keterangan: columns[8],
                                        statusSiswa: columns[11],
                                        kelas: columns[5],
                                        jumlah: parseFloat(columns[10].replace(/,/g, '')) || 0, // Pastikan jumlah diubah menjadi angka
                                        jenisBayar: columns[12],
                                    };
                                })
                                .sort((a, b) => a.namaPeserta.localeCompare(b.namaPeserta)); // Urutkan berdasarkan Nama Peserta

                            // Tambahkan objek untuk menyimpan total per jenis pembayaran
                            let totalPerJenisBayar = {};

                            // Menambahkan baris data yang telah difilter dan diurutkan
                            doc.setFont("helvetica", "normal");
                            let no = 1;
                            let totalPembayaran = 0; // Variabel untuk menghitung total pembayaran

                            sortedRows.forEach(data => {
                                // Cek apakah keterangan mengandung "Byr.Kursus"
                                if (data.keterangan.includes("Byr.Kursus")) {
                                    if (y + lineSpacing > pageHeight - 20) {
                                        doc.addPage(); // Tambah halaman baru jika mencapai batas bawah
                                        y = 20; // Reset posisi Y pada halaman baru
                                    }
                                    // Menampilkan data ke dalam PDF
                                    const tanggalTransaksiFormatted = formatDate(data.tanggal); // Format tanggal
                                    doc.text(no.toString(), 14, y);                // No
                                    doc.text(tanggalTransaksiFormatted, 20, y);   // Tanggal
                                    doc.text(data.idPeserta, 50, y);              // ID Peserta
                                    doc.text(data.namaPeserta, 75, y);            // Nama Peserta
                                    doc.text(data.keterangan, 113, y);            // Keterangan
                                    doc.text(data.statusSiswa, 185, y);           // Status Siswa
                                    doc.text(data.kelas, 215, y);                 // Kelas
                                    doc.text(data.jumlah.toLocaleString('id-ID'), 230, y); // Jumlah dalam format lokal
                                    doc.text(data.jenisBayar, 255, y);            // Jenis Bayar

                                    // Hitung total per jenis pembayaran
                                    if (!totalPerJenisBayar[data.jenisBayar]) {
                                        totalPerJenisBayar[data.jenisBayar] = 0;
                                    }
                                    totalPerJenisBayar[data.jenisBayar] += data.jumlah;

                                    totalPembayaran += data.jumlah; // Tambahkan jumlah ke total
                                    y += lineSpacing;
                                    no++;
                                }
                            });

                            if (y + 20 > pageHeight - 20) {
                                doc.addPage();
                                y = 20;
                            }

                            // Garis horizontal di bawah data terakhir
                            doc.line(14, y, 287, y); // Garis horizontal di bawah semua data
                            y += 10;

                            // Menambahkan Total Pembayaran per Jenis Bayar
                            doc.setFontSize(12);
                            doc.setFont("helvetica", "bold");
                            doc.text('Total per Jenis Pembayaran:', 14, y);
                            y += 7;

                            doc.setFont("helvetica", "normal"); // Set kembali font ke normal

                            // Menampilkan total tiap jenis pembayaran tanpa bold
                            Object.keys(totalPerJenisBayar).forEach(jenis => {
                                if (y + 10 > pageHeight - 20) {
                                    doc.addPage();
                                    y = 20;
                                }
                                doc.text(`- ${jenis}`, 20, y); // Jenis Pembayaran (Indentasi 20)
                                doc.text(`Rp ${totalPerJenisBayar[jenis].toLocaleString('id-ID')}`, 253, y, { align: 'right' }); // Nilai sejajar di kanan
                                y += 7;
                            });

                            // Garis horizontal terakhir sebelum total pembayaran keseluruhan
                            doc.line(14, y, 287, y);
                            y += 10;

                            // Menambahkan Total Pembayaran Keseluruhan
                            doc.setFontSize(12);
                            doc.setFont("helvetica", "bold");
                            doc.text('Total Pembayaran:', 185, y);
                            doc.text(`Rp ${totalPembayaran.toLocaleString('id-ID')}`, 253, y, { align: 'right' });
                            y += 10;

                            // Simpan PDF
                            doc.save('laporan_pembayaran_kursus.pdf');
                        });

                        document.getElementById('printPdfBtnBelumMembayar').addEventListener('click', function() {
                            const { jsPDF } = window.jspdf;
                            const doc = new jsPDF('landscape', 'mm', 'a4'); // Format landscape

                            let y = 20; // Posisi awal dalam PDF
                            const pageHeight = doc.internal.pageSize.height;
                            const lineSpacing = 10;

                            // Header PDF
                            doc.setFontSize(18);
                            doc.text('Laporan Peserta Belum Membayar', 14, y);
                            y += 10;

                            // Header tabel dalam PDF
                            doc.setFont("helvetica", "bold");
                            doc.setFontSize(12);
                            doc.text('No', 14, y);
                            doc.text('ID Peserta', 25, y);
                            doc.text('Nama Peserta', 55, y);
                            doc.text('Tanggal', 105, y);
                            doc.text('Kelas', 140, y);
                            doc.text('Tahun', 165, y);
                            doc.text('Bulan', 190, y);
                            doc.text('Keterangan', 210, y);
                            doc.text('Jumlah Bayar', 255, y);
                            y += 5;
                            doc.line(14, y, 287, y);
                            y += 5;

                            let no = 1;
                            const tableRows = document.querySelectorAll('#table-body tr');

                            // Data peserta (ID Peserta -> Nama Peserta)
                            const pesertaData = {
                                @foreach ($pesertas as $peserta)
                                "{{ $peserta->id_peserta }}": "{{ $peserta->nama }}",
                                @endforeach
                            };

                            // Fungsi untuk memformat tanggal tanpa waktu
                            function formatTanggal(dateString) {
                                const date = new Date(dateString);
                                if (isNaN(date.getTime())) return '-';
                                return date.toISOString().split('T')[0]; // Hanya mengambil YYYY-MM-DD
                            }

                            // Ambil hanya peserta yang terlihat (belum membayar)
                            tableRows.forEach(row => {
                                if (row.style.display !== 'none') {
                                    const idPeserta = row.querySelector('td:nth-child(1)').textContent.trim();
                                    const namaPeserta = pesertaData[idPeserta] || '-';
                                    const tanggal = formatTanggal(row.querySelector('td:nth-child(5)').textContent.trim());
                                    const kelas = row.querySelector('td:nth-child(4)').textContent.trim();
                                    const tahun = row.querySelector('td:nth-child(7)').textContent.trim();
                                    const bulan = row.querySelector('td:nth-child(8)').textContent.trim();
                                    const keterangan = row.querySelector('td:nth-child(9)').textContent.trim();
                                    const jumlah = "Rp. " + row.querySelector('td:nth-child(11)').textContent.trim();

                                    if (y + lineSpacing > pageHeight - 20) {
                                        doc.addPage();
                                        y = 20;
                                    }

                                    doc.setFont("helvetica", "normal");
                                    doc.setFontSize(10);
                                    doc.text(no.toString(), 14, y);
                                    doc.text(idPeserta, 25, y);
                                    doc.text(namaPeserta, 55, y);
                                    doc.text(tanggal, 105, y);
                                    doc.text(kelas, 140, y);
                                    doc.text(tahun, 165, y);
                                    doc.text(bulan, 190, y);
                                    doc.text(keterangan, 210, y);
                                    doc.text(jumlah, 255, y);

                                    y += lineSpacing;
                                    no++;
                                }
                            });

                            // Jika tidak ada data yang ditampilkan
                            if (no === 1) {
                                doc.setFontSize(12);
                                doc.setFont("helvetica", "bold");
                                doc.text("Tidak ada peserta yang belum membayar.", 14, y);
                            }

                            // Simpan PDF secara otomatis
                            doc.save('laporan_belum_membayar.pdf');
                        });


                    });
                </script>


    @endpush
