@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Edit Biaya Kelas') }}</h1>
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
                <form action="{{ route('admin.peserta.updatebiaya', $kelas->id_kelas) }}" method="POST" >
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_kelas">{{ __('Nama Kelas') }}</label>
                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" value="{{ $kelas->nama_kelas }}" readonly />
                    </div>
                    <div class="form-group">
                        <label for="biaya_pendaftaran">{{ __('Pendaftaran') }}</label>
                        <input type="text" class="form-control" id="biaya_pendaftaran" placeholder="{{ __('Rp. 100,000') }}" name="biaya_pendaftaran" value="{{$kelas->biaya_pendaftaran}}" />
                    </div>
                    <div class="form-group">
                        <label for="biaya_bulanan_tahunan">{{ __('Bulanan') }}</label>
                        <input type="text" class="form-control" id="biaya_bulanan_tahunan" placeholder="{{ __('Rp. 1,600,000') }}" name="biaya_bulanan_tahunan" value="{{ old('biaya_bulanan_tahunan', $kelas->biaya_bulanan_tahunan) }}" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Simpan') }}</button>
                </form>
            </div>
        </div>


    <!-- Content Row -->

</div>

<script>
    // document.getElementById('pelajaran').addEventListener('change', function() {
    //     const pelajaran = this.value;
    //     const kelasReadonlyField = document.getElementById('kelas_readonly');
    //     kelasReadonlyField.value = pelajaran; // Set value input readonly sesuai dropdown pelajaran
    // });
</script>
@endsection
