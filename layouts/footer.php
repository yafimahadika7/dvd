</div> <!-- penutup .content -->
</div> <!-- penutup .row -->
</div> <!-- penutup .container-fluid -->

<footer class="app-footer">
    © UNPAM 2025 — Aplikasi Rental DVD - UAS Pemrograman II • <strong>Rangga Roris | 07TPLE004 | 221011401293</strong>
</footer>

<!-- SCRIPT JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function logoutConfirm() {
    Swal.fire({
        title: 'Logout?',
        text: 'Anda yakin ingin keluar?',
        icon: 'warning',
        width: '260px',
        padding: '0.5rem',
        heightAuto: false,
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= base_url('/auth/logout.php') ?>";
        }
    })
}
</script>

</body>
</html>
