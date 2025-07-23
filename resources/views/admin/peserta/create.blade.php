@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Tambah Peserta') }}</h1>
        <a href="{{ route('admin.peserta.index') }}" class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
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
                <form action="{{ route('admin.peserta.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="id_peserta">{{ __('Kode Siswa') }}</label>
                        <input type="text" class="form-control" id="id_peserta" name="id_peserta" readonly />
                    </div>

                    <div class="form-group">
                        <label for="kelas">{{ __('Pelajaran') }}</label>
                        <select class="form-control" id="kelas" name="kelas">
                            <option value="CV" {{ old('pelajaran') == 'CV' ? 'selected' : '' }}>{{ __('CV') }}</option>
                            <option value="EL" {{ old('pelajaran') == 'EL' ? 'selected' : '' }}>{{ __('EL') }}</option>
                            <option value="HSK" {{ old('pelajaran') == 'HSK' ? 'selected' : '' }}>{{ __('HSK') }}</option>
                            <option value="IV" {{ old('pelajaran') == 'IV' ? 'selected' : '' }}>{{ __('IV') }}</option>
                            <option value="KD" {{ old('pelajaran') == 'KD' ? 'selected' : '' }}>{{ __('KD') }}</option>
                            <option value="PV" {{ old('pelajaran') == 'PV' ? 'selected' : '' }}>{{ __('PV') }}</option>
                            <option value="TA" {{ old('pelajaran') == 'TA' ? 'selected' : '' }}>{{ __('TA') }}</option>
                            <option value="REG" {{ old('pelajaran') == 'REG' ? 'selected' : '' }}>{{ __('REG') }}</option>
                            <option value="1A" {{ old('pelajaran') == '1A' ? 'selected' : '' }}>{{ __('1A') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama">{{ __('Nama Siswa') }}</label>
                        <input type="text" class="form-control" id="nama" placeholder="{{ __('Nama') }}" name="nama" value="{{ old('nama') }}" />
                    </div>
                    <div class="form-group">
                        <label for="nama_panggilan">{{ __('Panggilan') }}</label>
                        <input type="text" class="form-control" id="nama_panggilan" placeholder="{{ __('Panggilan') }}" name="nama_panggilan" value="{{ old('nama_panggilan') }}" />
                    </div>
                    <div class="form-group">
                        <label for="tmpt_lahir">{{ __('Tempat Lahir') }}</label>
                        <input type="text" class="form-control" id="tmpt_lahir" placeholder="{{ __('Bandung') }}" name="tmpt_lahir" value="{{ old('tmpt_lahir') }}" required />
                    </div>

                    <div class="form-group">
                        <label for="tgl_lahir">{{ __('Tanggal Lahir') }}</label>
                        <input type="date" class="form-control" id="tgl_lahir" placeholder="{{ __('2003-09-20') }}" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required />
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <label for="asal_sekolah">{{ __('Sekolah/Universitas/Pekerjaan') }}</label>
                            <input type="text" class="form-control" id="asal_sekolah" placeholder="{{ __('Sekolah') }}" name="asal_sekolah" value="{{ old('sekolah') }}" required />
                        </div>


                        <div class="col-md-4">
                            <label for="kelas">{{ __('Kelas') }}</label>
                            <input type="text" class="form-control" id="kelas_readonly" name="kelas" readonly value="{{ old('kelas') }}" />
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="alamat">{{ __('Alamat Siswa') }}</label>
                        <input type="text" class="form-control" id="alamat" placeholder="{{ __('Alamat Siswa') }}" name="alamat" value="{{ old('alamat') }}" required />
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <label for="kota">{{ __('Kota') }}</label>
                            <input type="text" class="form-control" id="kota" placeholder="{{ __('Kota') }}" name="kota" value="{{ old('kota') }}" required />
                        </div>

                        <div class="col-md-4">
                            <label for="kodepos">{{ __('Kode Pos') }}</label>
                            <input type="text" class="form-control" id="kodepos" placeholder="{{ __('Kode Pos') }}" name="kodepos" value="{{ old('kodepos') }}" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="email" placeholder="{{ __('Email') }}" name="email" value="{{ old('email') }}" required />
                    </div>
                    <div class="form-group">
                        <label for="telp">{{ __('Telepon Siswa') }}</label>
                        <input type="text" class="form-control" id="telp" placeholder="{{ __('Telepon Siswa') }}" name="telp" value="{{ old('telp') }}" required />
                    </div>

                    <div class="form-group">
                        <label for="gender">{{ __('Gender') }}</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="Laki-Laki" {{ old('gender') == 'Laki-Laki' ? 'selected' : '' }}>{{ __('Laki-Laki') }}</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>{{ __('Perempuan') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pndkan">{{ __('Pendidikan Terakhir') }}</label>
                        <select class="form-control" id="pndkan" name="pndkan">
                            <option value="TK" {{ old('pndkan') == 'TK' ? 'selected' : '' }}>{{ __('TK') }}</option>
                            <option value="SD" {{ old('pndkan') == 'SD' ? 'selected' : '' }}>{{ __('SD') }}</option>
                            <option value="SMP" {{ old('pndkan') == 'SMP' ? 'selected' : '' }}>{{ __('SMP') }}</option>
                            <option value="SMA" {{ old('pndkan') == 'SMA' ? 'selected' : '' }}>{{ __('SMA') }}</option>
                            <option value="DIPLOMA" {{ old('pndkan') == 'DIPLOMA' ? 'selected' : '' }}>{{ __('DIPLOMA') }}</option>
                            <option value="SARJANA" {{ old('pndkan') == 'SARJANA' ? 'selected' : '' }}>{{ __('SARJANA') }}</option>
                            <option value="MAGISTER" {{ old('pndkan') == 'MAGISTER' ? 'selected' : '' }}>{{ __('MAGISTER') }}</option>
                            <option value="DOKTOR" {{ old('pndkan') == 'DOKTOR' ? 'selected' : '' }}>{{ __('DOKTOR') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kelastrakhir">{{ __('Kelas Terakhir') }}</label>
                        <select class="form-control" id="kelastrakhir" name="kelastrakhir">
                            <option value="CV" {{ old('kelastrakhir') == 'CV' ? 'selected' : '' }}>{{ __('CV') }}</option>
                            <option value="EL" {{ old('kelastrakhir') == 'EL' ? 'selected' : '' }}>{{ __('EL') }}</option>
                            <option value="HSK" {{ old('kelastrakhir') == 'HSK' ? 'selected' : '' }}>{{ __('HSK') }}</option>
                            <option value="IV" {{ old('kelastrakhir') == 'IV' ? 'selected' : '' }}>{{ __('IV') }}</option>
                            <option value="KD" {{ old('kelastrakhir') == 'KD' ? 'selected' : '' }}>{{ __('KD') }}</option>
                            <option value="PV" {{ old('kelastrakhir') == 'PV' ? 'selected' : '' }}>{{ __('PV') }}</option>
                            <option value="TA" {{ old('kelastrakhir') == 'TA' ? 'selected' : '' }}>{{ __('TA') }}</option>
                            <option value="REG" {{ old('kelastrakhir') == 'REG' ? 'selected' : '' }}>{{ __('REG') }}</option>
                            <option value="1A" {{ old('kelastrakhir') == '1A' ? 'selected' : '' }}>{{ __('1A') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="haribljr">{{ __('Hari Belajar') }}</label>
                        <input type="text" class="form-control" id="haribljr" placeholder="{{ __('Hari Belajar') }}" name="haribljr" value="{{ old('haribelajar') }}" required />
                    </div>
                    <div class="form-group">
                        <label for="mulaibljr">{{ __('Tanggal Mulai Belajar') }}</label>
                        <input type="date" class="form-control" id="mulaibljr" placeholder="{{ __('Tanggal Mulai Belajar') }}" name="mulaibljr" value="{{ old('mulaibljr') }}" />
                    </div>
                    <div class="form-group">
                        <label for="lamabljr">{{ __('Lama Belajar') }}</label>
                        <input type="text" class="form-control" id="lamabljr" placeholder="{{ __('Lama Belajar') }}" name="lamabljr" value="{{ old('lamabljr') }}" required />
                    </div>

                    <div class="form-group">
                        <label for="status">{{ __('Status Siswa') }}</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>{{ __('Aktif') }}</option>
                            <option value="Cuti" {{ old('status') == 'Cuti' ? 'selected' : '' }}>{{ __('Cuti') }}</option>
                            <option value="Berhenti" {{ old('status') == 'Berhenti' ? 'selected' : '' }}>{{ __('Berhenti') }}</option>
                            <option value="Lulus" {{ old('status') == 'Lulus' ? 'selected' : '' }}>{{ __('Lulus') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">{{ __('Simpan') }}</button>
                </form>
            </div>
        </div>


    <!-- Content Row -->

</div>
{{--<script>--}}
{{--    document.getElementById('kelas').addEventListener('change', function() {--}}
{{--        const kelas = this.value;--}}
{{--        const idPesertaField = document.getElementById('id_peserta');--}}

{{--        if (kelas) {--}}
{{--            const filteredPeserta = pesertaData.filter(p => p.id_peserta.startsWith(kelas));--}}

{{--            // Format ID baru misalnya berdasarkan kelas--}}
{{--            const newId = kelas + '-' + String(Math.floor(Math.random() * 10000)).padStart(5, '0');--}}
{{--            idPesertaField.value = newId;--}}
{{--        } else {--}}
{{--            idPesertaField.value = '';--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}

<script>
    document.getElementById('kelas').addEventListener('change', async function () {
        const kelas = this.value; // Mendapatkan nilai kelas yang dipilih
        const idPesertaField = document.getElementById('id_peserta');

        if (kelas) {
            try {
                // Panggil API untuk mendapatkan ID terakhir
                const response = await fetch(`/get-last-id?kelas=${kelas}`);
                const data = await response.json();

                let nextId;
                if (data.last_id) {
                    // Ambil angka terakhir dari ID dan tambahkan 1
                    const lastNumber = parseInt(data.last_id.split('-')[1], 10);
                    nextId = kelas + '-' + String(lastNumber + 1).padStart(5, '0');
                } else {
                    // Jika tidak ada ID sebelumnya, mulai dari 1
                    nextId = kelas + '-' + '00001';
                }

                // Setel nilai ID peserta baru ke input field
                idPesertaField.value = nextId;
            } catch (error) {
                console.error('Error fetching last ID:', error);
                idPesertaField.value = '';
            }
        } else {
            idPesertaField.value = '';
        }
    });
</script>

<script>
    document.getElementById('kelas').addEventListener('change', function() {
        const pelajaran = this.value;
        const kelasReadonlyField = document.getElementById('kelas_readonly');
        kelasReadonlyField.value = pelajaran; // Set value input readonly sesuai dropdown pelajaran
    });
</script>


{{--Script untuk menset tanggal mulai belajar tidak boleh mengisi tanggal yang sudah lewat--}}
<script>
    document.getElementById('mulaibljr').addEventListener('input', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset to midnight for comparison

        if (selectedDate < today) {
            alert('{{ __('Tanggal mulai belajar tidak boleh mengisi tanggal yang sudah lewat.') }}');
            this.value = '';
        }
    });
</script>


{{--<script>--}}
{{--    document.getElementById('pelajaran').addEventListener('change', function() {--}}
{{--        var pelajaran = this.value;--}}
{{--        var prefix = '';--}}
{{--        switch(pelajaran) {--}}
{{--            case 'CV': prefix = 'C'; break;--}}
{{--            case 'EL': prefix = 'E'; break;--}}
{{--            case 'HSK': prefix = 'H'; break;--}}
{{--            case 'IV': prefix = 'I'; break;--}}
{{--            case 'KD': prefix = 'K'; break;--}}
{{--            case 'PV': prefix = 'P'; break;--}}
{{--            case 'TA': prefix = 'T'; break;--}}
{{--            case 'REG': prefix = 'R'; break;--}}
{{--            case '1A': prefix = 'A'; break;--}}
{{--            default: prefix = '';--}}
{{--        }--}}

{{--        // Fetch last code (Assuming you have data on last code in the page)--}}
{{--        var lastCode = getLastCode(pelajaran);--}}

{{--        // Generate new code for siswa--}}
{{--        var newCode = prefix + '-' + ('00000' + (parseInt(lastCode.split('-')[1]) + 1)).slice(-5);--}}

{{--        document.getElementById('kodesiswa').value = newCode;--}}
{{--    });--}}

{{--    function getLastCode(pelajaran) {--}}
{{--        // This function should get the last code of the selected pelajaran--}}
{{--        // You may want to fetch it via AJAX or from a hidden variable if possible--}}
{{--        // For now, let's assume it's '00000' for demonstration purposes--}}
{{--        return '00000'; // Replace this with actual logic to get last code for the pelajaran--}}
{{--    }--}}
{{--</script>--}}

@endsection
