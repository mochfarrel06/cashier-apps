@extends('auth.layouts.master')

@section('content')
    <!-- Layout Login -->
    <x-auth.auth-layout :title="'Aplikasi Kasir Via Vio'" :route="'store'" />
    <!-- End Layout Login -->
@endsection
