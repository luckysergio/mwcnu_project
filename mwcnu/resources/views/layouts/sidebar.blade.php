<style>
    .bg-custom-green {
        background-color: #1cc88a;
        background-image: linear-gradient(180deg, #1cc88a 10%, #17a673 100%);
        background-size: cover;
    }

    .nav-link .badge-counter {
        position: absolute;
        transform: translate(-50%, -50%);
        top: 10px;
        right: 5px;
    }

    .nav-link {
        position: relative;
    }
</style>

<ul class="navbar-nav bg-custom-green sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <div class="sidebar-brand d-flex align-items-center justify-content-center flex-column text-center">
        {{-- <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo MWCNU" class="img-fluid"
                style="max-height: 80px; object-fit: contain;">
        </div> --}}
        <div class="sidebar-brand-text mt-2" style="font-size: 14px; font-weight: bold;">MWCNU KARANG TENGAH</div>
    </div>


    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->is('anggota') ? 'active' : '' }}">
        <a class="nav-link" href="/anggota">
            <i class="fas fa-fw fa-users"></i>
            <span>Anggota</span>
        </a>
    </li>
    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->is('proker') ? 'active' : '' }}">
        <a class="nav-link" href="/proker">
            <i class="fas fa-fw fa-list"></i>
            <span>Program kerja</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('data-proker') ? 'active' : '' }}">
        <a class="nav-link" href="/data-proker">
            <i class="fas fa-fw fa-list"></i>
            <span>Data Program Kerja</span>
        </a>
    </li>

    <hr class="sidebar-divider my-0">

    @auth
        @if (auth()->user()->role_id == 1)
            <li class="nav-item {{ request()->is('proker-request') ? 'active' : '' }}">
                <a class="nav-link" href="/proker-request">
                    <i class="fas fa-fw fa-paper-plane"></i>
                    <span>Pengajuan program kerja</span>
                    <span id="prokerRequestBadge" class="badge badge-danger badge-counter" style="display: none;"></span>
                </a>
            </li>
        @endif
    @endauth

    <script>
        function fetchProkerRequests() {
            fetch('/count-submitted-proker')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('prokerRequestBadge');
                    if (data.count > 0) {
                        badge.style.display = 'inline-block';
                        badge.textContent = data.count;
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', fetchProkerRequests);

        setInterval(fetchProkerRequests, 600000);
    </script>

    @auth
        @if (auth()->user()->role_id == 1)
            <li class="nav-item {{ request()->is('jadwal') ? 'active' : '' }}">
                <a class="nav-link d-flex justify-content-between align-items-center" href="/jadwal">
                    <div>
                        <i class="fas fa-calendar-alt"></i>
                        <span>Jadwal Program Kerja</span>
                    </div>
                    <span id="belumJadwalBadge" class="badge badge-danger badge-counter" style="display: none;"></span>
                </a>
            </li>
        @endif
    @endauth

    <script>
        function fetchBelumJadwalProker() {
            fetch('/count-proker-belum-jadwal')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('belumJadwalBadge');
                    if (data.count > 0) {
                        badge.style.display = 'inline-block';
                        badge.textContent = data.count;
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', fetchBelumJadwalProker);
        setInterval(fetchBelumJadwalProker, 600000);
    </script>

    <li class="nav-item {{ request()->is('anggaran') ? 'active' : '' }}">
        <a class="nav-link" href="/anggaran">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Laporan anggaran</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
