<x-sidebar.layout>
    <x-sidebar.title :name="'ViaVio'" :icon="'fas fa-solid fa-book'" :addRoute="'cashier.dashboard.index'" />

    <x-sidebar.nav-item route="cashier.dashboard.index" icon="fa-tachometer-alt" label="Dashboard" />

    <x-sidebar.nav-item route="cashier.transaction.index" icon="fa-shop" label="Kasir" />

    <x-sidebar.nav-item title="Master" icon="fa-shop" label="Laporan" collapseId="collapseItem" :routes="['cashier.report.*', 'cashier.report-income.*']"
        :subItems="[
            ['route' => 'cashier.report.dailyReport', 'label' => 'Laporan Harian'],
            ['route' => 'cashier.report-income.income', 'label' => 'Pendapatan Harian'],
        ]" />
</x-sidebar.layout>
