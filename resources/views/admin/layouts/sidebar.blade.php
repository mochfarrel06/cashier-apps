<!-- Sidebar -->
<x-sidebar.layout>
    <!-- Sidebar title -->
    <x-sidebar.title :name="'FarrCode'" :icon="'fas fa-solid fa-book'" :addRoute="'admin.dashboard'" />
    <!-- End sidebar title -->

    <!-- Nav item dashboard -->
    <x-sidebar.nav-item route="admin.dashboard" icon="fa-tachometer-alt" label="Dashboard" />
    <!-- End nav item dashboard -->

    <!-- Nav item barang -->
    <x-sidebar.nav-item title="Master" icon="fa-box" label="Barang" collapseId="collapseItem" :routes="['admin.product.*']"
        :subItems="[['route' => 'admin.product.index', 'label' => 'Data Barang']]" />
    <!-- End nav item -->

    <!-- Nav item incoming item -->
    {{-- <x-sidebar.nav-item title="Transaksi" icon="fa-folder" label="Transaksi" collapseId="collapsePages" :routes="['admin.incoming-item.*', 'admin.outgoing-item.*']"
        :subItems="[
            ['route' => 'admin.incoming-item.index', 'label' => 'Barang Masuk'],
            ['route' => 'admin.outgoing-item.index', 'label' => 'Barang Keluar'],
        ]" /> --}}
    <!-- End nav item incoming item -->

    <!-- Nav item report -->
    {{-- <x-sidebar.nav-item title="Laporan" icon="fa-wrench" label="Laporan" collapseId="collapseReport" :routes="['admin.item-report.*', 'admin.incoming-report.*', 'admin.outgoing-report.*']"
        :subItems="[
            ['route' => 'admin.item-report.index', 'label' => 'Laporan Data Barang'],
            ['route' => 'admin.incoming-report.index', 'label' => 'Laporan Barang Masuk'],
            ['route' => 'admin.outgoing-report.index', 'label' => 'Laporan Barang Keluar'],
        ]" /> --}}
    <!-- End nav item report -->

    <!-- Heading -->
    {{-- <div class="sidebar-heading">
        Manajemen Pengguna
    </div>

    <li class="nav-item {{ request()->routeIs('admin.user-management.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.user-management.index') }}">
            <i class="fas fa-fw fa-solid fa-user"></i>
            <span>Pengguna</span></a>
    </li> --}}
    <!-- End nav item report -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

</x-sidebar.layout>
