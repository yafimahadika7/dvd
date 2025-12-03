<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin','petugas']);

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE id = :id");
$stmt->execute(['id' => $id]);
$pel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pel) {
    die("Data tidak ditemukan");
}

$pageTitle = "Edit Pelanggan";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = $_POST['nama_pelanggan'];
    $alamat  = $_POST['alamat'];
    $telp    = $_POST['telepon'];
    $email   = $_POST['email'];

    $update = $pdo->prepare("
        UPDATE pelanggan
        SET nama_pelanggan = :nama_pelanggan,
            alamat = :alamat,
            telepon = :telepon,
            email = :email
        WHERE id = :id
    ");
    $update->execute([
        'nama_pelanggan' => $nama,
        'alamat'         => $alamat,
        'telepon'        => $telp,
        'email'          => $email,
        'id'             => $id
    ]);

    echo '<div class="alert alert-success">Data pelanggan berhasil diupdate.</div>';
}
?>

<h3>Edit Pelanggan</h3>
<form method="post">
  <div class="mb-3">
    <label>Nama Pelanggan</label>
    <input type="text" name="nama_pelanggan" class="form-control" required
           value="<?= htmlspecialchars($pel['nama_pelanggan']) ?>">
  </div>
  <div class="mb-3">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control"><?= htmlspecialchars($pel['alamat']) ?></textarea>
  </div>
  <div class="mb-3">
    <label>Telepon</label>
    <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($pel['telepon']) ?>">
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($pel['email']) ?>">
  </div>
  <button class="btn btn-primary">Update</button>
  <a href="<?= base_url('/pages/pelanggan/index.php') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php
include '../../layouts/footer.php';
?>
