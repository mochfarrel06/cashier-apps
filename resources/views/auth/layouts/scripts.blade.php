 <!-- Bootstrap core JavaScript-->
 <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
 <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

 <!-- Core plugin JavaScript-->
 <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

 <!-- Custom scripts for all pages-->
 <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

 <script src="{{ asset('assets/vendor/izitoast/js/iziToast.min.js') }}"></script>

 <script>
     document.getElementById('togglePassword').addEventListener('click', function() {
         let passwordInput = document.getElementById('exampleInputPassword');
         let passwordIcon = this.querySelector('i');
         if (passwordInput.type === 'password') {
             passwordInput.type = 'text';
             passwordIcon.classList.remove('fa-eye');
             passwordIcon.classList.add('fa-eye-slash');
         } else {
             passwordInput.type = 'password';
             passwordIcon.classList.remove('fa-eye-slash');
             passwordIcon.classList.add('fa-eye');
         }
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
