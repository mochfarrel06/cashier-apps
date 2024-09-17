<!-- Sidebar -->
<x-sidebar.layout>
    <!-- Sidebar title -->
    <x-sidebar.title :name="'ViaVio'" :icon="'fas fa-solid fa-book'" :addRoute="'admin.dashboard'" />
    <!-- End sidebar title -->

    <!-- Nav item dashboard -->
    <x-sidebar.nav-item route="admin.dashboard" icon="fa-tachometer-alt" label="Dashboard" />
    <!-- End nav item dashboard -->

    <!-- Nav item Produk -->
    <x-sidebar.nav-item title="Master" icon="fa-box" label="Produk" collapseId="collapseItem" :routes="['admin.product.*', 'admin.flavor.*', 'admin.cashier-product.*']"
        :subItems="[
            ['route' => 'admin.product.index', 'label' => 'Data Produk'],
            ['route' => 'admin.flavor.index', 'label' => 'Varian Produk'],
            ['route' => 'admin.cashier-product.index', 'label' => 'Produk kasir'],
        ]" />
    <!-- End nav item -->

    <!-- Nav item carts -->
    <x-sidebar.nav-item title="Laporan" icon="fa-file" label="Laporan" collapseId="collapsePages" :routes="['admin.report.*']"
        :subItems="[['route' => 'admin.report.detailReport', 'label' => 'Laporan Transaksi']]" />
    <!-- End nav item carts -->

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
    {{-- <hr class="sidebar-divider d-none d-md-block"> --}}

</x-sidebar.layout>
