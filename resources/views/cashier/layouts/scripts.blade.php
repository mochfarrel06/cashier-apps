 <!-- Scroll to Top Button-->
 <a class="scroll-to-top rounded" href="#page-top">
     <i class="fas fa-angle-up"></i>
 </a>

 <!-- Bootstrap core JavaScript-->
 <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
 <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

 <!-- Core plugin JavaScript-->
 <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

 <!-- Custom scripts for all pages-->
 <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

 <!-- Page level plugins -->
 <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
 <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

 <!-- Page level custom scripts -->
 <script src="{{ asset('assets/js/demo/datatables-demo.js') }}"></script>

 <script src="https://kit.fontawesome.com/363895cb1f.js" crossorigin="anonymous"></script>

 <!-- SweetAlert Library for Beautiful Alerts -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <script src="{{ asset('assets/vendor/izitoast/js/iziToast.min.js') }}"></script>

 <script>
     $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
     });

     $(document).ready(function() {
         $('body').on('click', '.delete-item', function(e) {
             e.preventDefault(); // Prevent default action
             let url = $(this).attr('href'); // Get URL from href attribute
             let row = $(this).closest('tr'); // Get the row to be deleted

             Swal.fire({
                 title: "Apakah anda ingin menghapus data?",
                 text: "",
                 icon: "warning",
                 showCancelButton: true,
                 confirmButtonColor: "#3085d6",
                 cancelButtonColor: "#d33",
                 confirmButtonText: "Delete"
             }).then((result) => {
                 if (result.isConfirmed) {
                     // AJAX request to delete item
                     $.ajax({
                         method: 'DELETE',
                         url: url,
                         data: {
                             "_token": "{{ csrf_token() }}", // Add CSRF token
                         },
                         success: function(response) {
                             if (response.status === 'success') {
                                 iziToast.success({
                                     title: 'Success',
                                     message: response.message,
                                     position: 'topRight'
                                 });
                                 row.remove(); // Remove the item from the table

                                 // Update row indices
                                 $('#dataTable tbody tr').each(function(index) {
                                     $(this).find('.index').text(index + 1);
                                 });
                             } else if (response.status === 'error') {
                                 iziToast.error({
                                     title: 'Error',
                                     message: response.message,
                                     position: 'topRight'
                                 });
                             }
                         },
                         error: function(error) {
                             iziToast.error({
                                 title: 'Error',
                                 message: 'Terjadi kesalahan saat menghapus data. Silakan coba lagi nanti.',
                                 position: 'topRight'
                             });
                         }
                     });
                 }
             });
         });
     });

     $(document).ready(function() {
         @if (session('success'))
             iziToast.success({
                 title: 'Berhasil',
                 message: '{{ session('success') }}',
                 position: 'topRight'
             });
         @endif

         @if (session('error'))
             iziToast.error({
                 title: 'Error',
                 message: '{{ session('error') }}',
                 position: 'topRight'
             });
         @endif

         @if (session('info'))
             iziToast.info({
                 title: 'Info',
                 message: '{{ session('info') }}',
                 position: 'topRight'
             });
         @endif
     });
 </script>

 @stack('scripts')
