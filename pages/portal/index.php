<?php
require_once '../../config/config.php';
cek_login();
cek_role(['pelanggan']);

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE user_id = :uid LIMIT 1");
$stmt->execute(['uid' => $user_id]);
$pel = $stmt->fetch(PDO::FETCH_ASSOC);

$pel_id = $pel['id'] ?? 0;

$countTrans = 0;
if ($pel_id) {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM transaksi_sewa WHERE id_pelanggan = :pid");
    $countStmt->execute(['pid' => $pel_id]);
    $countTrans = $countStmt->fetchColumn();
}

$pageTitle = "Portal Pelanggan";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<h3 class="mb-4"><i class="bi bi-house-door"></i> Portal Pelanggan</h3>

<?php if (!$pel): ?>
  <div class="alert alert-warning">
    Profil pelanggan belum dikaitkan dengan akun ini. Silakan hubungi petugas.
  </div>
<?php else: ?>
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3">Profil Anda</h5>
      <table class="table">
        <tr><th>Nama</th><td><?= htmlspecialchars($pel['nama_pelanggan']) ?></td></tr>
        <tr><th>Alamat</th><td><?= htmlspecialchars($pel['alamat']) ?></td></tr>
        <tr><th>Telepon</th><td><?= htmlspecialchars($pel['telepon']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($pel['email']) ?></td></tr>
      </table>
    </div>
  </div>

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3"><i class="bi bi-clock-history"></i> Ringkasan Sewa</h5>
      <p>Total transaksi sewa yang tercatat: <strong><?= $countTrans ?></strong></p>
      <a href="<?= base_url('/pages/portal/riwayat.php') ?>" class="btn btn-primary btn-sm">Lihat Riwayat Lengkap</a>
    </div>
  </div>
<?php endif; ?>

<?php
include '../../layouts/footer.php';
?>
