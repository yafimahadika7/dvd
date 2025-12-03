<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin','petugas']);

$pageTitle = "Tambah DVD";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

$kategori = $pdo->query("SELECT * FROM kategori_dvd ORDER BY nama_kategori")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul   = $_POST['judul'];
    $id_kat  = $_POST['id_kategori'] ?: null;
    $tahun   = $_POST['tahun_rilis'] ?: null;
    $stok    = (int) $_POST['stok'];
    $harga   = (float) $_POST['harga_sewa'];

    $stmt = $pdo->prepare("
        INSERT INTO dvd (judul, id_kategori, tahun_rilis, stok, harga_sewa)
        VALUES (:judul, :id_kategori, :tahun_rilis, :stok, :harga_sewa)
    ");
    $stmt->execute([
        'judul'        => $judul,
        'id_kategori'  => $id_kat,
        'tahun_rilis'  => $tahun,
        'stok'         => $stok,
        'harga_sewa'   => $harga
    ]);

    echo '<div class="alert alert-success">Data DVD berhasil disimpan.</div>';
}
?>

<h3>Tambah DVD</h3>
<form method="post">
  <div class="mb-3">
    <label>Judul DVD</label>
    <input type="text" name="judul" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Kategori</label>
    <select name="id_kategori" class="form-select">
      <option value="">-- Pilih Kategori --</option>
      <?php foreach ($kategori as $k): ?>
        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Tahun Rilis</label>
    <input type="number" name="tahun_rilis" class="form-control" min="1900" max="<?= date('Y') ?>">
  </div>
  <div class="mb-3">
    <label>Stok</label>
    <input type="number" name="stok" class="form-control" min="0" value="0">
  </div>
  <div class="mb-3">
    <label>Harga Sewa</label>
    <input type="number" name="harga_sewa" class="form-control" min="0" value="0">
  </div>
  <button class="btn btn-primary">Simpan</button>
  <a href="<?= base_url('/pages/dvd/index.php') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php
include '../../layouts/footer.php';
?>
