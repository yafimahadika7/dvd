<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin','petugas']);

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM dvd WHERE id = :id");
$stmt->execute(['id' => $id]);
$dvd = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dvd) {
    die("Data tidak ditemukan");
}

$kategori = $pdo->query("SELECT * FROM kategori_dvd ORDER BY nama_kategori")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Edit DVD";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul   = $_POST['judul'];
    $id_kat  = $_POST['id_kategori'] ?: null;
    $tahun   = $_POST['tahun_rilis'] ?: null;
    $stok    = (int) $_POST['stok'];
    $harga   = (float) $_POST['harga_sewa'];

    $update = $pdo->prepare("
        UPDATE dvd
        SET judul = :judul,
            id_kategori = :id_kategori,
            tahun_rilis = :tahun_rilis,
            stok = :stok,
            harga_sewa = :harga_sewa
        WHERE id = :id
    ");
    $update->execute([
        'judul'        => $judul,
        'id_kategori'  => $id_kat,
        'tahun_rilis'  => $tahun,
        'stok'         => $stok,
        'harga_sewa'   => $harga,
        'id'           => $id
    ]);

    echo '<div class="alert alert-success">Data DVD berhasil diupdate.</div>';
}
?>

<h3>Edit DVD</h3>
<form method="post">
  <div class="mb-3">
    <label>Judul DVD</label>
    <input type="text" name="judul" class="form-control" required
           value="<?= htmlspecialchars($dvd['judul']) ?>">
  </div>
  <div class="mb-3">
    <label>Kategori</label>
    <select name="id_kategori" class="form-select">
      <option value="">-- Pilih Kategori --</option>
      <?php foreach ($kategori as $k): ?>
        <option value="<?= $k['id'] ?>" <?= $k['id'] == $dvd['id_kategori'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($k['nama_kategori']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Tahun Rilis</label>
    <input type="number" name="tahun_rilis" class="form-control" min="1900" max="<?= date('Y') ?>"
           value="<?= htmlspecialchars($dvd['tahun_rilis']) ?>">
  </div>
  <div class="mb-3">
    <label>Stok</label>
    <input type="number" name="stok" class="form-control" min="0"
           value="<?= htmlspecialchars($dvd['stok']) ?>">
  </div>
  <div class="mb-3">
    <label>Harga Sewa</label>
    <input type="number" name="harga_sewa" class="form-control" min="0"
           value="<?= htmlspecialchars($dvd['harga_sewa']) ?>">
  </div>
  <button class="btn btn-primary">Update</button>
  <a href="<?= base_url('/pages/dvd/index.php') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php
include '../../layouts/footer.php';
?>
