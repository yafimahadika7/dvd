<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin','petugas']);

$pageTitle = "Tambah Pelanggan";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = $_POST['nama_pelanggan'];
    $alamat  = $_POST['alamat'];
    $telp    = $_POST['telepon'];
    $email   = $_POST['email'];

    $stmt = $pdo->prepare("
        INSERT INTO pelanggan (nama_pelanggan, alamat, telepon, email)
        VALUES (:nama_pelanggan, :alamat, :telepon, :email)
    ");
    $stmt->execute([
        'nama_pelanggan' => $nama,
        'alamat'         => $alamat,
        'telepon'        => $telp,
        'email'          => $email
    ]);

    echo '<div class="alert alert-success">Data pelanggan berhasil disimpan.</div>';
}
?>

<h3>Tambah Pelanggan</h3>
<form method="post">
  <div class="mb-3">
    <label>Nama Pelanggan</label>
    <input type="text" name="nama_pelanggan" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control"></textarea>
  </div>
  <div class="mb-3">
    <label>Telepon</label>
    <input type="text" name="telepon" class="form-control">
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control">
  </div>
  <button class="btn btn-primary">Simpan</button>
  <a href="<?= base_url('/pages/pelanggan/index.php') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php
include '../../layouts/footer.php';
?>
