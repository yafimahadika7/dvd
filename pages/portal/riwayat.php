<?php
require_once '../../config/config.php';
cek_login();
cek_role(['pelanggan']);

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE user_id = :uid LIMIT 1");
$stmt->execute(['uid' => $user_id]);
$pel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pel) {
    die("Profil pelanggan tidak ditemukan. Hubungi petugas.");
}

$pel_id = $pel['id'];

$trans = $pdo->prepare("
    SELECT * FROM transaksi_sewa
    WHERE id_pelanggan = :pid
    ORDER BY id DESC
");
$trans->execute(['pid' => $pel_id]);

$pageTitle = "Riwayat Sewa";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<h3 class="mb-4"><i class="bi bi-clock-history"></i> Riwayat Sewa</h3>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode</th>
      <th>Tgl Sewa</th>
      <th>Jatuh Tempo</th>
      <th>Tgl Kembali</th>
      <th>Status</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = $trans->fetch(PDO::FETCH_ASSOC)) : ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['kode_transaksi']) ?></td>
        <td><?= htmlspecialchars($row['tgl_sewa']) ?></td>
        <td><?= htmlspecialchars($row['tgl_jatuh_tempo']) ?></td>
        <td><?= htmlspecialchars($row['tgl_kembali']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= number_format($row['total'], 0, ',', '.') ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<a href="<?= base_url('/pages/portal/index.php') ?>" class="btn btn-secondary">Kembali</a>

<?php
include '../../layouts/footer.php';
?>
