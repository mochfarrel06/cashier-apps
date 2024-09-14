<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="codeFa">
<meta name="author" content="codeFa">
<title>@yield('title-page')</title>

<link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">

<!-- Custom fonts for this template-->
<link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<!-- Custom styles for this template-->
<link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
<!-- Custom styles for this page -->
<link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/vendor/izitoast/css/iziToast.min.css') }}">

<style>
    .image-upload-wrapper {
        position: relative;
        width: 100%;
        height: 250px;
        border: 2px dashed #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
        text-align: center;
        background-color: #f8f9fa;
    }

    .image-upload-wrapper input[type="file"] {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .image-upload-text {
        font-size: 18px;
        color: #aaa;
        font-weight: bold;
        display: block;
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 1px solid #ccc;
    }
</style>
