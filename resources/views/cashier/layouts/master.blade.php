<!DOCTYPE html>
<html lang="en">

<head>
    @include('cashier.layouts.head')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <x-master.wrapper>
        @include('cashier.layouts.sidebar')

        <!-- Content Wrapper -->
        <x-master.content-wrapper>
            <!-- Main Content -->
            <x-master.content>
                <!-- Topbar -->
                <x-navbar :routeActive="'admin.profile.*'" :routeLink="'admin.profile.index'" routeStore="{{ route('destroy') }}" />
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->
            </x-master.content>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('cashier.layouts.footer')
            <!-- End of Footer -->
        </x-master.content-wrapper>
        <!-- End of Content Wrapper -->
    </x-master.wrapper>
    <!-- End of Page Wrapper -->

    @include('cashier.layouts.scripts')
</body>

</html>
