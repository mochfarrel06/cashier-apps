<!-- Sidebar -->
<x-sidebar.layout>
    <!-- Sidebar title -->
    <x-sidebar.title :name="'ViaVio'" :icon="'fas fa-solid fa-book'" :addRoute="'cashier.dashboard.index'" />
    <!-- End sidebar title -->

    <!-- Nav item dashboard -->
    {{-- <x-sidebar.nav-item route="cashier.dashboard.index" icon="fa-tachometer-alt" label="Dashboard" /> --}}
    <!-- End nav item dashboard -->

    <x-sidebar.nav-item route="cashier.transaction.index" icon="fa-shop" label="Kasir" />

    <!-- Nav item Produk -->
    <x-sidebar.nav-item title="Master" icon="fa-shop" label="Laporan" collapseId="collapseItem" :routes="['cashier.report.*']"
        :subItems="[['route' => 'cashier.report.dailyReport', 'label' => 'Laporan Harian']]" />
    <!-- End nav item -->

    <!-- Nav item carts -->
    {{-- <x-sidebar.nav-item title="Kasir" icon="fa-shop" label="Kasir" collapseId="collapsePages" :routes="['admin.cart.*', 'admin.cart-product.*']"
        :subItems="[
            ['route' => 'admin.cart.index', 'label' => 'Data Kasir'],
            ['route' => 'admin.cart-product.index', 'label' => 'Produk Kasir'],
        ]" /> --}}
    <!-- End nav item carts -->

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
    {{-- <hr class="sidebar-divider d-none d-md-block"> --}}

</x-sidebar.layout>
