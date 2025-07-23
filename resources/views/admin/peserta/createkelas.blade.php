@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __('Tambah Kelas') }}</h1>
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
                <form action="{{ route('admin.peserta.storetambahkelas') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="kode_kelas">{{ __('Kelas') }}</label>
                        <input type="text" class="form-control" id="kode_kelas" name="kode_kelas"/>
                    </div>

                    <div class="form-group">
                        <label for="nama_kelas">{{ __('Nama Kelas') }}</label>
                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas"/>
                    </div>

                    <div class="form-group">
                        <label for="harga_pendaftaran">{{ __('Pendaftaran') }}</label>
                        <input type="text" class="form-control" id="harga_pendaftaran" placeholder="{{ __('Rp. 100,000') }}" name="harga_pendaftaran" value="{{ old('harga_pendaftaran') }}" />
                    </div>
                    <div class="form-group">
                        <label for="harga_bulanan">{{ __('Bulanan') }}</label>
                        <input type="text" class="form-control" id="harga_bulanan" placeholder="{{ __('Rp. 1,600,000') }}" name="harga_bulanan" value="{{ old('harga_bulanan') }}" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Simpan') }}</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // document.getElementById('kelas').addEventListener('change', async function () {
        //     const kelas = this.value; // Mendapatkan nilai kelas yang dipilih
        //     const idPesertaField = document.getElementById('id_peserta');
        //
        //     if (kelas) {
        //         try {
        //             // Panggil API untuk mendapatkan ID terakhir
        //             const response = await fetch(`/get-last-id?kelas=${kelas}`);
        //             const data = await response.json();
        //
        //             let nextId;
        //             if (data.last_id) {
        //                 // Ambil angka terakhir dari ID dan tambahkan 1
        //                 const lastNumber = parseInt(data.last_id.split('-')[1], 10);
        //                 nextId = kelas + '-' + String(lastNumber + 1).padStart(5, '0');
        //             } else {
        //                 // Jika tidak ada ID sebelumnya, mulai dari 1
        //                 nextId = kelas + '-' + '00001';
        //             }
        //
        //             // Setel nilai ID peserta baru ke input field
        //             idPesertaField.value = nextId;
        //         } catch (error) {
        //             console.error('Error fetching last ID:', error);
        //             idPesertaField.value = '';
        //         }
        //     } else {
        //         idPesertaField.value = '';
        //     }
        // });
    </script>

    <script>
        // document.getElementById('kelas').addEventListener('change', function() {
        //     const pelajaran = this.value;
        //     const kelasReadonlyField = document.getElementById('kelas_readonly');
        //     kelasReadonlyField.value = pelajaran; // Set value input readonly sesuai dropdown pelajaran
        // });
    </script>


    {{--Script untuk menset tanggal mulai belajar tidak boleh mengisi tanggal yang sudah lewat--}}
    <script>
        {{--document.getElementById('mulaibljr').addEventListener('input', function() {--}}
        {{--    const selectedDate = new Date(this.value);--}}
        {{--    const today = new Date();--}}
        {{--    today.setHours(0, 0, 0, 0); // Reset to midnight for comparison--}}

        {{--    if (selectedDate < today) {--}}
        {{--        alert('{{ __('Tanggal mulai belajar tidak boleh mengisi tanggal yang sudah lewat.') }}');--}}
        {{--        this.value = '';--}}
        {{--    }--}}
        {{--});--}}
    </script>

@endsection
