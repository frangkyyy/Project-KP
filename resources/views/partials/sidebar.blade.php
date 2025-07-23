<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-text mx-3">{{ __('Pembayaran Uang Kursus') }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @php
        $isAdmin = auth()->user()->name === 'admin';
    @endphp

    @if ($isAdmin)
        <!-- Jika login sebagai admin, hanya tampilkan Data Siswa dan Kelola User -->
        <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseKelolaBiaya" aria-expanded="true" aria-controls="collapseKelolaBiaya">
                <i class="fas fa-user"></i>
                <span>{{ __('Kelola Biaya') }}</span>
            </a>
            <div id="collapseKelolaBiaya" class="collapse" aria-labelledby="headingKelolaBiaya" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->is('admin/peserta') || request()->is('admin/peserta/*') ? 'active' : '' }}" href="{{ route('admin.peserta.index') }}">
                        <i class="fas fa-users mr-2"></i>{{ __('Kelola Biaya') }}
                    </a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePenetapan" aria-expanded="true" aria-controls="collapsePenetapan">
                <i class="fas fa-wallet"></i>
                <span>{{ __('Penetapan') }}</span>
            </a>
            <div id="collapsePenetapan" class="collapse" aria-labelledby="headingPenetapan" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->is('admin/penetapan') ? 'active' : '' }}" href="{{ route('admin.penetapan.index') }}"><i class="fas fa-users mr-2"></i>{{ __('Penetapan') }}</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePembayaran" aria-expanded="true" aria-controls="collapsePembayaran">
                <i class="fas fa-credit-card"></i>
                <span>{{ __('Pembayaran') }}</span>
            </a>
            <div id="collapsePembayaran" class="collapse" aria-labelledby="headingPembayaran" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->is('admin/pembayaran') ? 'active' : '' }}" href="{{ route('admin.pembayaran.indexpembayaran') }}"><i class="fas fa-wallet"></i>{{ __('Proses Pembayaran') }}</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseKelolaUser" aria-expanded="true" aria-controls="collapseKelolaUser">
                <i class="fas fa-user-circle"></i>
                <span>{{ __('Kelola User') }}</span>
            </a>
            <div id="collapseKelolaUser" class="collapse" aria-labelledby="headingKelolaUser" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->is('admin/users') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users mr-2"></i>{{ __('Kelola User') }}
                    </a>
                </div>
            </div>
        </li>
    @else
        <!-- Jika bukan admin, tampilkan semua menu -->
        <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseDataSiswa" aria-expanded="true" aria-controls="collapseDataSiswa">
                <i class="fas fa-user"></i>
                <span>{{ __('Data Siswa') }}</span>
            </a>
            <div id="collapseDataSiswa" class="collapse" aria-labelledby="headingDataSiswa" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->is('admin/peserta') ? 'active' : '' }}" href="{{ route('admin.peserta.index') }}">
                        <i class="fas fa-users mr-2"></i>{{ __('Data Peserta') }}
                    </a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePenetapan" aria-expanded="true" aria-controls="collapsePenetapan">
                <i class="fas fa-wallet"></i>
                <span>{{ __('Penetapan') }}</span>
            </a>
            <div id="collapsePenetapan" class="collapse" aria-labelledby="headingPenetapan" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->is('admin/penetapan') ? 'active' : '' }}" href="{{ route('admin.penetapan.index') }}">
                        <i class="fas fa-users mr-2"></i>{{ __('Penetapan') }}
                    </a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePembayaran" aria-expanded="true" aria-controls="collapsePembayaran">
                <i class="fas fa-credit-card"></i>
                <span>{{ __('Pembayaran') }}</span>
            </a>
            <div id="collapsePembayaran" class="collapse" aria-labelledby="headingPembayaran" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->is('admin/pembayaran') ? 'active' : '' }}" href="{{ route('admin.pembayaran.indexpembayaran') }}">
                        <i class="fas fa-wallet"></i>{{ __('Proses Pembayaran') }}
                    </a>
                </div>
            </div>
        </li>
    @endif
</ul>
