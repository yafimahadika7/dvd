<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin','petugas','pemilik']);

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT t.*, p.nama_pelanggan
    FROM transaksi_sewa t
    JOIN pelanggan p ON t.id_pelanggan = p.id
    WHERE t.id = :id
");
$stmt->execute(['id' => $id]);
$t = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$t) {
    die("Data tidak ditemukan");
}

$det = $pdo->prepare("
    SELECT d.*, ds.jumlah, ds.harga
    FROM detail_sewa ds
    JOIN dvd d ON ds.id_dvd = d.id
    WHERE ds.id_transaksi = :id
");
$det->execute(['id' => $id]);

$pageTitle = "Detail Transaksi";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<h3>Detail Transaksi</h3>
<table class="table table-bordered">
  <tr><th>Kode</th><td><?= htmlspecialchars($t['kode_transaksi']) ?></td></tr>
  <tr><th>Pelanggan</th><td><?= htmlspecialchars($t['nama_pelanggan']) ?></td></tr>
  <tr><th>Tanggal Sewa</th><td><?= htmlspecialchars($t['tgl_sewa']) ?></td></tr>
  <tr><th>Jatuh Tempo</th><td><?= htmlspecialchars($t['tgl_jatuh_tempo']) ?></td></tr>
  <tr><th>Tanggal Kembali</th><td><?= htmlspecialchars($t['tgl_kembali']) ?></td></tr>
  <tr><th>Status</th><td><?= htmlspecialchars($t['status']) ?></td></tr>
  <tr><th>Total</th><td><?= number_format($t['total'], 0, ',', '.') ?></td></tr>
</table>

<h5>Detail DVD</h5>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Judul</th>
      <th>Jumlah</th>
      <th>Harga</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($d = $det->fetch(PDO::FETCH_ASSOC)) : 
        $sub = $d['jumlah'] * $d['harga'];
    ?>
      <tr>
        <td><?= htmlspecialchars($d['judul']) ?></td>
        <td><?= htmlspecialchars($d['jumlah']) ?></td>
        <td><?= number_format($d['harga'], 0, ',', '.') ?></td>
        <td><?= number_format($sub, 0, ',', '.') ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<a href="<?= base_url('/pages/transaksi/index.php') ?>" class="btn btn-secondary">Kembali</a>

<?php
include '../../layouts/footer.php';
?>
