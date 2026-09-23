{{-- Include this at the bottom of any view, below the SweetAlert2 CDN script.
     If your layouts/app.blade.php doesn't already load SweetAlert2, add:
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            confirmButtonColor: '#5b21b6',
            timer: 3500,
            timerProgressBar: true,
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops',
            text: @json(session('error')),
            confirmButtonColor: '#5b21b6',
        });
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Please check the form',
            html: @json($errors->first()),
            confirmButtonColor: '#5b21b6',
        });
    </script>
@endif
