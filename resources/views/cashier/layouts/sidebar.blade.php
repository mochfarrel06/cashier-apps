<x-sidebar.layout>
    <x-sidebar.title :name="'ViaVio'" :icon="'fas fa-solid fa-book'" :addRoute="'cashier.dashboard.index'" />

    <x-sidebar.nav-item route="cashier.dashboard.index" icon="fa-tachometer-alt" label="Dashboard" />

    <div class="sidebar-heading">
        Kasir
    </div>
    <x-sidebar.nav-item route="cashier.transaction.index" icon="fa-shop" label="Kasir" />

    <x-sidebar.nav-item title="Master" icon="fa-file" label="Laporan" collapseId="collapseItem" :routes="['cashier.transaction-report.*', 'cashier.income-report.*']"
        :subItems="[
            ['route' => 'cashier.transaction-report.index', 'label' => 'Laporan Transaksi'],
            ['route' => 'cashier.income-report.index', 'label' => 'Laporan Pendapatan'],
        ]" />
</x-sidebar.layout>
