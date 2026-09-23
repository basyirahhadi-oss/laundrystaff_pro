

<?php if(session('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: <?php echo json_encode(session('success'), 15, 512) ?>,
            confirmButtonColor: '#5b21b6',
            timer: 3500,
            timerProgressBar: true,
        });
    </script>
<?php endif; ?>

<?php if(session('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops',
            text: <?php echo json_encode(session('error'), 15, 512) ?>,
            confirmButtonColor: '#5b21b6',
        });
    </script>
<?php endif; ?>

<?php if($errors->any()): ?>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Please check the form',
            html: <?php echo json_encode($errors->first(), 15, 512) ?>,
            confirmButtonColor: '#5b21b6',
        });
    </script>
<?php endif; ?>
<?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/partials/flash-alerts.blade.php ENDPATH**/ ?>