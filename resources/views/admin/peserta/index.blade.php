@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                @if(auth()->user()->name == 'admin')
                    Data Peserta & Kelola User
                @else
                    Data Peserta
                @endif
            </h1>
                <a href="{{ route('admin.peserta.create') }}" class="btn btn-success ml-auto">
            <span class="icon text-white-50">
                <i class="fa fa-plus"></i>
            </span>
                    <span class="text">{{ __('Tambah Peserta') }}</span>
                </a>
        </div>

        <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('Peserta') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable datatable-User" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Kode Siswa') }}</th>
                            <th>{{ __('Nama Siswa') }}</th>
                            <th>{{ __('Nama Panggilan') }}</th>
                            <th>{{ __('Gender') }}</th>
                            <th>{{ __('Mulai Belajar') }}</th>
                            <th>{{ __('Id_Belajar') }}</th>
                            <th>{{ __('Tempat Lahir') }}</th>
                            <th>{{ __('Tanggal Lahir') }}</th>
                            <th>{{ __('Kelas Terakhir') }}</th>
                            <th>{{ __('Hari Belajar') }}</th>
                            <th>{{ __('Sekolah') }}</th>
                            <th>{{ __('Kelas') }}</th>
                            <th>{{ __('Pendidikan Akhir') }}</th>
                            <th>{{ __('Alamat') }}</th>
                            <th>{{ __('Kota') }}</th>
                            <th>{{ __('Telp') }}</th>
                            <th>{{ __('Status Siswa') }}</th>
                            <th>{{ __('Pendaftaran') }}</th>
                            <th>{{ __('Bulanan') }}</th>
                            @if(auth()->user()->name !== 'admin')
                                <th>{{ __('Action') }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pesertas as $peserta)
                            <tr data-entry-id="{{ $peserta->id_peserta }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $peserta->id_peserta }}</td>
                                <td>{{ $peserta->nama }}</td>
                                <td>{{ $peserta->nama_panggilan }}</td>
                                <td>{{ $peserta->jenis_kelamin }}</td>
                                <td>{{ $peserta->tgl_mulai_belajar }}</td>
                                <td>{{ $peserta->id_kelas }}</td> {{--id belajar --}}
                                <td>{{ $peserta->tmpt_lahir }}</td>
                                <td>{{ $peserta->tgl_lahir }}</td>
                                <td>{{ $peserta->id_kelas }}</td> {{-- kelas terakhir --}}
                                <td>{{ $peserta->jadwal_belajar }}</td>
                                <td>{{ $peserta->asal_sekolah }}</td>
                                <td>{{ $peserta->id_kelas }}</td>
                                <td>{{ $peserta->tingkat_terakhir_pendidikan }}</td>
                                <td>{{ $peserta->alamat }}</td>
                                <td>{{ $peserta->kota }}</td>
                                <td>{{ $peserta->nohp_ortu_wali }}</td>
                                <td>{{ $peserta->status_siswa }}</td>
                                <td>{{ $peserta->kelas ? $peserta->kelas->biaya_pendaftaran : '-' }}</td> {{-- pendaftaran --}}
                                <td>{{ $peserta->kelas ? $peserta->kelas->biaya_bulanan_tahunan : '-' }}</td> {{-- bulanan --}}

                                    <td>
                                        <a href="{{ route('admin.peserta.edit', $peserta->id_peserta) }}" class="btn btn-primary">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.peserta.destroy', $peserta->id_peserta) }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
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

        <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('Tarif Pendaftaran dan Bulanan') }}
                </h6>
{{--                @if(auth()->user()->name === 'admin')--}}
{{--                    <a href="{{ route('admin.peserta.createkelas') }}" class="btn btn-success btn-sm">--}}
{{--                        <i class="fa fa-plus"></i> Tambah Kelas--}}
{{--                    </a>--}}
{{--                @endif--}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable datatable-User" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>{{ __('Kelas') }}</th>
                            <th>{{ __('Nama Kelas') }}</th>
                            <th>{{ __('Pendaftaran') }}</th>
                            <th>{{ __('Bulanan') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($kelass as $kelas)
                            <tr data-entry-id="{{ $kelas->id_kelas }}">
                                <td>{{ $kelas->id_kelas }}</td>
                                <td>{{ $kelas->nama_kelas }}</td>
                                <td style="text-align: right;" class="text-end">Rp. {{ number_format($kelas->biaya_pendaftaran, 0, '.', ',') }}</td>
                                <td style="text-align: right;" class="text-end">Rp. {{ number_format($kelas->biaya_bulanan_tahunan, 0, '.', ',') }}</td>
                                <td>
                                    @if(auth()->user()->name == 'admin')
                                        <a href="{{ route('admin.peserta.editbiaya', $kelas->id_kelas) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('Data Kosong') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('script-alt')
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            let deleteButtonTrans = 'delete selected'
            let deleteButton = {
                text: deleteButtonTrans,
                {{--url: "{{ route('admin.peserta.mass_destroy') }}",--}}
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });
                    if (ids.length === 0) {
                        alert('Tidak ada yang dipilih')
                        return
                    }
                    if (confirm('Apakah anda yakin?')) {
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            method: 'POST',
                            url: config.url,
                            data: { ids: ids, _method: 'DELETE' }
                        }).done(function () { location.reload() })
                    }
                }
            }
            dtButtons.push(deleteButton)
            $.extend(true, $.fn.dataTable.defaults, {
                order: [[ 1, 'asc' ]],
                pageLength: 50,
            });
            $('.datatable-Peserta:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        })
    </script>
@endpush
