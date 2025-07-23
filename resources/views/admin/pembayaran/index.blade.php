@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __('Pembayaran Uang Kursus') }}</h1>
            {{--<a href="{{ route('admin.pembayaran.index') }}" class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>--}}
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Content Row -->
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('admin.penetapan.storepembayaran') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="searchNama">{{ __('Cari Nama Siswa') }}</label>
                        <!-- Input teks untuk mengetik nama siswa -->
                        <input type="text" class="form-control" id="searchNama" placeholder="{{ __('Ketik Nama Siswa') }}" oninput="autoSelectAndFilterOption()">

                        <!-- Dropdown siswa -->
                        <select class="form-control mt-2" id="carinama" name="carinama" onchange="showAddressAndDate(this)">
                            @foreach($pesertas as $peserta)
                                @if($peserta->bulan_tanpa_duplikat && $peserta->tahun_tanpa_duplikat)
                                    <option value="{{ $peserta->id_peserta }}"
                                            data-address="{{ $peserta->alamat }}"
                                            data-bulan-tanpa-duplikat="{{ $peserta->bulan_tanpa_duplikat }}"
                                            data-tahun-tanpa-duplikat="{{ $peserta->tahun_tanpa_duplikat }}"
                                            data-kelas="{{ $peserta->id_kelas }}"
                                            data-biaya-bulanan-tahunan="{{ $peserta->biaya_bulanan_tahunan }}"
                                            data-biaya-pendaftaran="{{ $peserta->biaya_pendaftaran }}"
                                            data-status-siswa="{{ $peserta->status_siswa }}">
                                        {{ $peserta->id_peserta }} - {{ $peserta->nama }} - {{ $peserta->jenis_kelamin }} - {{ $peserta->id_kelas }} - {{ $peserta->status_siswa }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Menampilkan Alamat Siswa yang Dipilih -->
                    <div class="form-group">
                        <label for="alamat">{{ __('Alamat') }}</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="" readonly />
                    </div>

                    <!-- Script untuk Menampilkan Alamat Berdasarkan Pilihan Nama -->
                    <script>
                        function showAddress(select) {
                            var selectedOption = select.options[select.selectedIndex];
                            var address = selectedOption.getAttribute('data-address');
                            document.getElementById('alamat').value = address ? address : '';
                        }
                    </script>

                    <div class="form-group">
                        <label for="bulantahun">{{ __('Bulan/Tahun') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="bulan" placeholder="{{ __('Bulan') }}" name="bulan" value="{{ old('bulan') }}" readonly />
                            <span class="input-group-text">/</span>
                            <input type="text" class="form-control" id="tahun" placeholder="{{ __('Tahun') }}" name="tahun" value="{{ old('tahun') }}" readonly />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kelas">{{ __('Kelas') }}</label>
                        <input type="text" class="form-control" id="kelas_readonly" name="kelas" readonly value="{{ old('kelas') }}" />
                    </div>

                    <input type="hidden" id="status_siswa" name="status_siswa" />

                    <script>
                        function autoSelectAndFilterOption() {
                            var input = document.getElementById('searchNama').value.toLowerCase();
                            var dropdown = document.getElementById('carinama');
                            var options = dropdown.options;
                            var matched = false;

                            for (var i = 0; i < options.length; i++) {
                                var optionText = options[i].textContent.toLowerCase();
                                if (optionText.includes(input) && input !== '') {
                                    options[i].style.display = 'block';
                                    if (!matched) {
                                        dropdown.value = options[i].value;
                                        showAddressAndDate(dropdown);
                                        matched = true;
                                    }
                                } else {
                                    options[i].style.display = 'none';
                                }
                            }

                            if (!matched) {
                                dropdown.value = "";
                            }
                        }

                        function showAddressAndDate(select) {
                            var selectedOption = select.options[select.selectedIndex];
                            if (!selectedOption) return;

                            var address = selectedOption.getAttribute('data-address');
                            var bulanTanpaDuplikat = selectedOption.getAttribute('data-bulan-tanpa-duplikat');
                            var tahunTanpaDuplikat = selectedOption.getAttribute('data-tahun-tanpa-duplikat');
                            var kelas = selectedOption.getAttribute('data-kelas'); // Ambil data-kelas dari opsi yang dipilih
                            var statusSiswa = selectedOption.getAttribute('data-status-siswa');
                            // var showBulanTahun = selectedOption.getAttribute('data-show-bulan-tahun'); // Ambil status apakah bisa tampilkan bulan dan tahun

                            var biayaKursus = selectedOption.getAttribute('data-biaya-bulanan-tahunan');
                            var biayaPendaftaran = selectedOption.getAttribute('data-biaya-pendaftaran');

                            // Set nilai biaya kursus
                            var biayaKursusCheckbox = document.getElementById('biayakursus');
                            var biayaKursusText = document.getElementById('amountText2');
                            biayaKursusCheckbox.checked = biayaKursus > 0;
                            biayaKursusText.textContent = biayaKursus.toLocaleString();

                            // Set nilai biaya pendaftaran
                            var registrasiCheckbox = document.getElementById('registrasi');
                            var biayaPendaftaranText = document.getElementById('amountText');
                            registrasiCheckbox.checked = biayaPendaftaran > 0;
                            biayaPendaftaranText.textContent = biayaPendaftaran.toLocaleString();

                            // Update total pembayaran
                            updateTotal();

                            // Menampilkan alamat
                            document.getElementById('alamat').value = address ? address : '';

                            // Menampilkan bulan dan tahun
                            document.getElementById('bulan').value = bulanTanpaDuplikat ? bulanTanpaDuplikat : '';
                            document.getElementById('tahun').value = tahunTanpaDuplikat ? tahunTanpaDuplikat : '';

                            // Menampilkan kelas
                            document.getElementById('kelas_readonly').value = kelas ? kelas : '';

                            document.getElementById('ket').value = "Op: {{ auth()->user()->name }}";

                            document.getElementById('status_siswa').value = statusSiswa;

                            // // Menampilkan biaya kursus
                            // document.getElementById('amountText2').innerText = biayaKursus ? biayaKursus : 0;
                        }

                        function toggleDiskonInput() {
                            var diskonCheckbox = document.getElementById('diskon');
                            var inputDiskonContainer = document.getElementById('inputDiskon');

                            // Tampilkan atau sembunyikan input nilai diskon
                            inputDiskonContainer.style.display = diskonCheckbox.checked ? 'inline-block' : 'none';

                            // Reset nilai diskon jika checkbox dinonaktifkan
                            if (!diskonCheckbox.checked) {
                                document.getElementById('nilaiDiskon').value = '';
                                updateTotal(); // Perbarui total pembayaran
                            }
                        }

                        function updateTotal() {
                            var total = 0;

                            // Ambil nilai biaya kursus
                            var biayaKursusCheckbox = document.getElementById('biayakursus');
                            var biayaKursusText = document.getElementById('amountText2');
                            var biayaKursus = parseInt(biayaKursusText.textContent || 0);
                            if (biayaKursusCheckbox.checked) {
                                var nilaiDiskon = document.getElementById('nilaiDiskon').value;
                                if (nilaiDiskon) {
                                    var diskonPersen = parseFloat(nilaiDiskon); // Ambil diskon sebagai float
                                    biayaKursus = biayaKursus - (biayaKursus * diskonPersen / 100); // Hitung setelah diskon
                                }
                                total += biayaKursus;;
                                biayaKursusText.style.display = 'inline'; // Tampilkan nilai
                            } else {
                                biayaKursusText.style.display = 'none'; // Sembunyikan nilai
                            }

                            // Ambil nilai biaya pendaftaran
                            var registrasiCheckbox = document.getElementById('registrasi');
                            var biayaPendaftaranText = document.getElementById('amountText');
                            if (registrasiCheckbox.checked) {
                                total += parseInt(biayaPendaftaranText.textContent || 0);
                                biayaPendaftaranText.style.display = 'inline'; // Tampilkan nilai
                            } else {
                                biayaPendaftaranText.style.display = 'none'; // Sembunyikan nilai
                            }

                            // Update input total pembayaran
                            document.getElementById('totalpembayaran').value = total > 0 ? total : '';
                        }
                    </script>


                    <div class="form-group d-flex align-items-center">
                        <input type="checkbox" id="registrasi" name="registrasi" value="1" onclick="updateTotal()"/>
                        <label for="registrasi" class="col-md-2 mb-0">{{ __('Registrasi') }}</label>
                        <span id="amountText" class="ms-1" style="display: none;"></span>
                    </div>

                    <div class="form-group d-flex align-items-center">
                        <input type="checkbox" id="biayakursus" name="biayakursus" value="1" onclick="updateTotal()"/>
                        <label for="biayakursus" class="col-md-2 mb-0">{{ __('Biaya Kursus') }}</label>
                        <span id="amountText2" class="ms-1" style="display: none;"></span>
                    </div>

                    <div class="form-group d-flex align-items-center">
                        <input type="checkbox" id="diskon" name="diskon" onclick="toggleDiskonInput()"/>
                        <label for="diskon" class="col-md-2 mb-0">{{ __('Diskon Kursus') }}</label>
                        <span id="inputDiskon" class="ms-2" style="display: none;">
                        <label for="nilaiDiskon" class="mb-0 me-2">{{ __('Nilai Diskon (%)') }}</label>
                        <input type="text" id="nilaiDiskon" name="nilaiDiskon" class="form-control d-inline w-auto"
                            style="display: inline-block;"
                            onchange="updateTotal()" />
                        </span>
                    </div>


                        <div class="form-group">
                        <label for="totalpembayaran">{{ __('Total Pembayaran') }}</label>
                        <input type="text" class="form-control" id="totalpembayaran" name="totalpembayaran" value="{{ old('totalpembayaran') }}" required readonly />
                    </div>

                    <div class="form-group">
                        <label for="jenispembayaran">{{ __('Jenis Pembayaran') }}</label>
                        <select class="form-control" id="jenispembayaran" name="jenispembayaran" required>
                            <option value="Trf BCA" {{ old('jenispembayaran') == 'Trf BCA' ? 'selected' : '' }}>{{ __('Trf BCA') }}</option>
                            <option value="Trf Mandiri" {{ old('jenispembayaran') == 'Trf Mandiri' ? 'selected' : '' }}>{{ __('Trf Mandiri') }}</option>
                            <option value="Deb BCA" {{ old('jenispembayaran') == 'Deb BCA' ? 'selected' : '' }}>{{ __('Deb BCA') }}</option>
                            <option value="Deb BNI" {{ old('jenispembayaran') == 'Deb BNI' ? 'selected' : '' }}>{{ __('Deb BNI') }}</option>
                            <option value="Deb Mandiri" {{ old('jenispembayaran') == 'Deb Mandiri' ? 'selected' : '' }}>{{ __('Deb Mandiri') }}</option>
                            <option value="KKredit" {{ old('jenispembayaran') == 'KKredit' ? 'selected' : '' }}>{{ __('KKredit') }}</option>
                            <option value="Lain-lain" {{ old('jenispembayaran') == 'Lain-lain' ? 'selected' : '' }}>{{ __('Lain-lain') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ket">{{ __('Keterangan') }}</label>
                        <input type="text" class="form-control" id="ket" placeholder="{{ __('Keterangan') }}" name="ket" value="Op: {{ auth()->user()->name }}" readonly />
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-info col-md-2" onclick="lihatKwitansi()">
                            {{ __('Lihat Kwitansi') }}
                        </button>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-info col-md-2" id="btnProses" disabled>
                            {{ __('Proses') }}
                        </button>
                    </div>

                    <input type="hidden" id="no_kwitansi" name="no_kwitansi">

                    <div class="modal fade" id="kwitansiModal" tabindex="-1" aria-labelledby="kwitansiModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="kwitansiModalLabel">{{ __('Rincian Kwitansi Pembayaran') }}</h5>
{{--                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="tutupKwitansi()"></button>--}}
                                </div>
                                <div class="modal-body">
                                    <p><strong>{{ __('No. Kwitansi:') }}</strong> <span id="modalNoKwitansi"></span></p>
                                    <p><strong>{{ __('Sudah diterima dari:') }}</strong> <span id="modalNama"></span></p>
                                    <p><strong>{{ __('Banyaknya Uang:') }}</strong> <span id="modalJumlahUang"></span></p>
                                    <p><strong>{{ __('Registrasi:') }}</strong> <span id="modalPendaftaran"></span></p>
                                    <p><strong>{{ __('Uang Kursus:') }}</strong> <span id="modalBiayaKursus"></span></p>
                                    <p><strong>{{ __('Diskon:') }}</strong> <span id="modalDiskon"></span></p>
                                    <p><strong>{{ __('Total Pembayaran:') }}</strong> Rp <span id="modalTotal"></span></p>
                                    <p><strong>{{ __('Jenis Pembayaran:') }}</strong> <span id="modalJenisPembayaran"></span></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="downloadKwitansiPDF()">{{ __('Cetak') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
        <script>
            let kwitansiDicetak = false; // Variabel untuk melacak apakah kwitansi sudah dicetak
            // SUMBER: CHATGPT
            function lihatKwitansi() {
                // Ambil nama siswa dan total pembayaran dari input form
                var namaSiswaDropdown = document.getElementById('carinama');
                var selectedOption = namaSiswaDropdown.options[namaSiswaDropdown.selectedIndex];
                var selectedText = selectedOption.textContent || "Tidak ada nama";

                // Memisahkan string berdasarkan tanda '-' dan mengambil nama siswa (bagian ke-2)
                var parts = selectedText.split(' - ');
                var namaSiswa = parts.length > 1 ? parts[1] : selectedText; // Ambil nama siswa (bagian kedua)

                // Ambil data biaya registrasi dan kursus dari input
                var biayaRegistrasi = document.getElementById('registrasi').checked
                    ? parseInt(document.getElementById('amountText').textContent || "0")
                    : 0;
                var biayaKursus = document.getElementById('biayakursus').checked
                    ? parseInt(document.getElementById('amountText2').textContent || "0")
                    : 0;
                // Ambil nilai diskon (default 0 jika tidak diinput)
                var diskonInput = document.getElementById('nilaiDiskon');
                var nilaiDiskon = diskonInput && diskonInput.value ? parseInt(diskonInput.value) : 0;

                var totalPembayaran = document.getElementById('totalpembayaran').value || "0";
                var jumlahuang = konversiKeKata(parseInt(totalPembayaran));

                // Ambil jenis pembayaran yang dipilih dari dropdown
                var jenisPembayaranDropdown = document.getElementById('jenispembayaran');
                var jenisPembayaran = jenisPembayaranDropdown.options[jenisPembayaranDropdown.selectedIndex].textContent;

                var today = new Date();
                var tahun = today.getFullYear();
                var bulan = String(today.getMonth() + 1).padStart(2, '0');
                var nomorAcak = Math.floor(Math.random() * 9999) + 1;
                var noKwitansi = `${tahun}${bulan}-${nomorAcak}`;

                // Setel nilai No. Kwitansi ke dalam input hidden
                document.getElementById('no_kwitansi').value = noKwitansi;

                // Tampilkan data ke modal
                document.getElementById('modalNoKwitansi').textContent = noKwitansi;
                document.getElementById('modalNama').textContent = namaSiswa;
                document.getElementById('modalJumlahUang').textContent = jumlahuang;
                document.getElementById('modalPendaftaran').textContent = biayaRegistrasi.toLocaleString();
                document.getElementById('modalBiayaKursus').textContent = biayaKursus.toLocaleString();
                document.getElementById('modalTotal').textContent = parseInt(totalPembayaran).toLocaleString();

                // Tambahkan jenis pembayaran ke modal (jika diperlukan, buat elemen baru di HTML modal)
                var modalJenisPembayaran = document.getElementById('modalJenisPembayaran');
                if (!modalJenisPembayaran) {
                    // Jika elemen belum ada, tambahkan
                    var modalBody = document.querySelector('.modal-body');
                    var newElement = document.createElement('p');
                    newElement.id = 'modalJenisPembayaran';
                    newElement.innerHTML = `${jenisPembayaran}`;
                    modalBody.appendChild(newElement);
                } else {
                    // Jika elemen sudah ada, perbarui isinya
                    modalJenisPembayaran.textContent = `${jenisPembayaran}`;
                }

                // Tambahkan nilai diskon ke modal
                var modalDiskon = document.getElementById('modalDiskon');
                if (!modalDiskon) {
                    var modalBody = document.querySelector('.modal-body');
                    var newDiskonElement = document.createElement('p');
                    newDiskonElement.id = 'modalDiskon';
                    newDiskonElement.innerHTML = `${nilaiDiskon.toLocaleString()}%`;
                    modalBody.appendChild(newDiskonElement);
                } else {
                    modalDiskon.textContent = `${nilaiDiskon.toLocaleString()}%`;
                }

                // Buka modal
                var kwitansiModal = new bootstrap.Modal(document.getElementById('kwitansiModal'));
                kwitansiModal.show();
            }

            // SUMBER: CHATGPT
            // Fungsi untuk mengkonversi angka menjadi teks
            function konversiKeKata(num) {
                const satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan"];
                const puluhan = ["Sepuluh", "Sebelas", "Dua Belas", "Tiga Belas", "Empat Belas", "Lima Belas", "Enam Belas", "Tujuh Belas", "Delapan Belas", "Sembilan Belas"];
                const duapuluhan = ["", "", "Dua Puluh", "Tiga Puluh", "Empat Puluh", "Lima Puluh", "Enam Puluh", "Tujuh Puluh", "Delapan Puluh", "Sembilan Puluh"];
                const thousands = ["", "Ribu", "Juta", "Miliar", "Triliun"];

                if (num === 0) return "Nol Rupiah";

                let words = "";

                let i = 0;
                while (num > 0) {
                    let remainder = num % 1000;
                    if (remainder !== 0) {
                        let part = konversikeratusan(remainder);
                        words = part + " " + thousands[i] + " " + words;
                    }
                    num = Math.floor(num / 1000);
                    i++;
                }

                return words.trim() + " Rupiah";
            }

            function konversikeratusan(num) {
                const satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan"];
                const puluhan = ["Sepuluh", "Sebelas", "Dua Belas", "Tiga Belas", "Empat Belas", "Lima Belas", "Enam Belas", "Tujuh Belas", "Delapan Belas", "Sembilan Belas"];
                const duapuluhan = ["", "", "Dua Puluh", "Tiga Puluh", "Empat Puluh", "Lima Puluh", "Enam Puluh", "Tujuh Puluh", "Delapan Puluh", "Sembilan Puluh"];

                if (num < 10) return satuan[num];
                if (num < 20) return puluhan[num - 10];
                if (num < 100) return duapuluhan[Math.floor(num / 10)] + " " + satuan[num % 10];
                return satuan[Math.floor(num / 100)] + " Ratus " + konversikeratusan(num % 100);
            }

            // SUMBER: CHATGPT
            async function downloadKwitansiPDF() {
                // Ambil data
                const noKwitansi = document.getElementById("modalNoKwitansi").textContent;
                const nama = document.getElementById("modalNama").textContent;
                const jumlahUang = document.getElementById("modalJumlahUang").textContent;
                const pendaftaran = document.getElementById("modalPendaftaran").textContent;
                const biayaKursus = document.getElementById("modalBiayaKursus").textContent;
                const diskon = document.getElementById("modalDiskon").textContent;
                const total = document.getElementById("modalTotal").textContent;
                const jenisPembayaran = document.getElementById("modalJenisPembayaran").textContent;

                // Dapatkan tanggal hari ini dalam format: 15 Januari 2025
                const hariini = new Date();
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                const tanggal = hariini.toLocaleDateString('id-ID', options);

                // Gunakan jsPDF untuk membuat PDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Atur isi dokumen PDF
                doc.setFont("helvetica", "normal");
                doc.setFontSize(14);
                doc.text("Kwitansi Pembayaran", 10, 10);
                doc.setFontSize(12);
                doc.text(`No. Kwitansi: ${noKwitansi}`, 10, 20);
                doc.text(`Sudah diterima dari: ${nama}`, 10, 30);
                doc.text(`Banyaknya Uang: ${jumlahUang}`, 10, 40);
                doc.text(`Registrasi: ${pendaftaran}`, 10, 50);
                doc.text(`Uang Kursus: ${biayaKursus}`, 10, 60);
                doc.text(`Diskon: ${diskon}`, 10, 70);
                doc.text(`Total Pembayaran: Rp ${total}`, 10, 85);
                doc.text(`Jenis Pembayaran: ${jenisPembayaran}`, 10, 95);
                doc.text("Terima kasih telah melakukan pembayaran.", 10, 115);

                // Menambahkan garis lurus di bawah nilai diskon
                const garisBawahDiskon = 70; // Posisi vertikal untuk diskon
                doc.setLineWidth(0.5); // Ketebalan garis
                doc.line(10, garisBawahDiskon + 5, 200, garisBawahDiskon + 5); // Garis horizontal

                // Menambahkan tanggal dan keterangan di bagian bawah kanan
                doc.setFontSize(10);
                doc.text(`Bandung, Jawa Barat, ${tanggal}`, 120, 135); // Menempatkan tanggal
                doc.text("Diterima oleh", 120, 145); // Menempatkan keterangan
                doc.text("Op: {{ auth()->user()->name }}", 120, 165); // Menempatkan tanda tangan staff

                // Menambahkan keterangan di sebelah kiri
                doc.setFontSize(10);
                doc.text("Keterangan:", 10, 130); // Menempatkan "Keterangan:" di sebelah kiri
                doc.text("Apa yang telah dibayarkan tidak dapat dikembalikan.", 10, 140); // Menambahkan kalimat keterangan
                doc.text("Op: {{ auth()->user()->name }}", 10, 150); // Menambahkan "Op: Staff"

                // Unduh file PDF
                doc.save(`Kwitansi_${nama}.pdf`);

                // Tandai kwitansi telah dicetak
                kwitansiDicetak = true;

                // Mengaktifkan tombol Proses setelah kwitansi dicetak
                document.getElementById('btnProses').disabled = false;
            }
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    </div>


@endsection


