<style>
    .bg-custom-green {
      background-color: #1cc88a;
      background-image: linear-gradient(180deg, #1cc88a 10%, #17a673 100%);
      background-size: cover;
    }
  </style>  

<ul class="navbar-nav bg-custom-green sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <div class="sidebar-brand d-flex align-items-center justify-content-center flex-column text-center">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo MWCNU" class="img-fluid" style="max-height: 80px; object-fit: contain;">
        </div>
        {{-- <div class="sidebar-brand-text mt-2" style="font-size: 14px; font-weight: bold;">KARANG TENGAH</div> --}}
    </div>
     

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{request()->is('dashboard') ? 'active' : ''}}">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    {{-- <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen data
    </div> --}}

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{request()->is('anggota') ? 'active' : ''}}">
        <a class="nav-link" href="/anggota">
            <i class="fas fa-fw fa-users"></i>
            <span>Anggota</span>
        </a>
    </li>

    <li class="nav-item {{request()->is('persetujuan-user') ? 'active' : ''}}">
        <a class="nav-link" href="/account-request">
            <i class="fas fa-fw fa-user"></i>
            <span>Pengajuan Akun</span>
        </a>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="/proker">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Program kerja</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>