<?php if (isset($_SESSION['mensaje'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['mensaje']['icono'] ?>',
            title: '<?= $_SESSION['mensaje']['titulo'] ?>',
            text: '<?= $_SESSION['mensaje']['texto'] ?>',
            timer: <?= $_SESSION['mensaje']['timer'] ?? 2500 ?>,
            showConfirmButton: <?= $_SESSION['mensaje']['confirmar'] ?? 'false' ?>,
        });
    </script>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>
