<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin','petugas']);

$pageTitle = "Tambah Transaksi";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

$pelanggan = $pdo->query("SELECT * FROM pelanggan ORDER BY nama_pelanggan")->fetchAll(PDO::FETCH_ASSOC);
$dvdList   = $pdo->query("SELECT * FROM dvd ORDER BY judul")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggan = $_POST['id_pelanggan'];
    $tgl_sewa     = $_POST['tgl_sewa'];
    $jatuh_tempo  = $_POST['tgl_jatuh_tempo'];
    $id_dvd       = $_POST['id_dvd'];
    $jumlah       = (int) $_POST['jumlah'];

    $kode = 'TRX' . date('YmdHis');

    $stmt = $pdo->prepare("
        INSERT INTO transaksi_sewa (kode_transaksi, id_pelanggan, tgl_sewa, tgl_jatuh_tempo, total, status)
        VALUES (:kode_transaksi, :id_pelanggan, :tgl_sewa, :tgl_jatuh_tempo, :total, 'pinjam')
    ");

    $dvdStmt = $pdo->prepare("SELECT harga_sewa FROM dvd WHERE id = :id");
    $dvdStmt->execute(['id' => $id_dvd]);
    $dvd = $dvdStmt->fetch(PDO::FETCH_ASSOC);
    $harga = $dvd ? (float)$dvd['harga_sewa'] : 0;
    $total = $harga * $jumlah;

    $stmt->execute([
        'kode_transaksi' => $kode,
        'id_pelanggan'   => $id_pelanggan,
        'tgl_sewa'       => $tgl_sewa,
        'tgl_jatuh_tempo'=> $jatuh_tempo,
        'total'          => $total
    ]);

    $id_transaksi = $pdo->lastInsertId();

    $detail = $pdo->prepare("
        INSERT INTO detail_sewa (id_transaksi, id_dvd, jumlah, harga)
        VALUES (:id_transaksi, :id_dvd, :jumlah, :harga)
    ");
    $detail->execute([
        'id_transaksi' => $id_transaksi,
        'id_dvd'       => $id_dvd,
        'jumlah'       => $jumlah,
        'harga'        => $harga
    ]);

    echo '<div class="alert alert-success">Transaksi berhasil disimpan.</div>';
}
?>

<h3>Tambah Transaksi</h3>
<form method="post">
  <div class="mb-3">
    <label>Pelanggan</label>
    <select name="id_pelanggan" class="form-select" required>
      <option value="">-- Pilih Pelanggan --</option>
      <?php foreach ($pelanggan as $p): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama_pelanggan']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Tanggal Sewa</label>
    <input type="date" name="tgl_sewa" class="form-control" value="<?= date('Y-m-d') ?>" required>
  </div>
  <div class="mb-3">
    <label>Tanggal Jatuh Tempo</label>
    <input type="date" name="tgl_jatuh_tempo" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>DVD</label>
    <select name="id_dvd" class="form-select" required>
      <option value="">-- Pilih DVD --</option>
      <?php foreach ($dvdList as $d): ?>
        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['judul']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Jumlah</label>
    <input type="number" name="jumlah" class="form-control" min="1" value="1">
  </div>
  <button class="btn btn-primary">Simpan</button>
  <a href="<?= base_url('/pages/transaksi/index.php') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php
include '../../layouts/footer.php';
?>
