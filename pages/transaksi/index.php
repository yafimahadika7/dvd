<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin','petugas','pemilik']);

$pageTitle = "Transaksi Sewa";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if (isset($_GET['hapus']) && $_SESSION['user_role'] !== 'pemilik') {
    $id = (int) $_GET['hapus'];
    $pdo->prepare("DELETE FROM detail_sewa WHERE id_transaksi = :id")->execute(['id' => $id]);
    $pdo->prepare("DELETE FROM transaksi_sewa WHERE id = :id")->execute(['id' => $id]);
    echo '<div class="alert alert-success">Transaksi berhasil dihapus.</div>';
}

if (isset($_GET['kembali']) && $_SESSION['user_role'] !== 'pemilik') {
    $id = (int) $_GET['kembali'];
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("UPDATE transaksi_sewa SET tgl_kembali = :tgl, status = 'kembali' WHERE id = :id");
    $stmt->execute(['tgl' => $today, 'id' => $id]);
    echo '<div class="alert alert-success">Transaksi ditandai sebagai kembali.</div>';
}

$stmt = $pdo->query("
    SELECT t.*, p.nama_pelanggan
    FROM transaksi_sewa t
    JOIN pelanggan p ON t.id_pelanggan = p.id
    ORDER BY t.id DESC
");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Transaksi Sewa</h3>
  <?php if ($_SESSION['user_role'] !== 'pemilik') : ?>
    <a href="<?= base_url('/pages/transaksi/tambah.php') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Transaksi</a>
  <?php endif; ?>
</div>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode</th>
      <th>Pelanggan</th>
      <th>Tgl Sewa</th>
      <th>Jatuh Tempo</th>
      <th>Status</th>
      <th>Total</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['kode_transaksi']) ?></td>
        <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
        <td><?= htmlspecialchars($row['tgl_sewa']) ?></td>
        <td><?= htmlspecialchars($row['tgl_jatuh_tempo']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= number_format($row['total'], 0, ',', '.') ?></td>
        <td>
          <a href="<?= base_url('/pages/transaksi/view.php?id=' . $row['id']) ?>" class="btn btn-sm btn-info">Detail</a>
          <?php if ($_SESSION['user_role'] !== 'pemilik') : ?>
            <?php if ($row['status'] === 'pinjam') : ?>
              <a href="<?= base_url('/pages/transaksi/index.php?kembali=' . $row['id']) ?>" class="btn btn-sm btn-success"
                 onclick="return confirm('Tandai sudah kembali?')">Kembali</a>
            <?php endif; ?>
            <a href="<?= base_url('/pages/transaksi/index.php?hapus=' . $row['id']) ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php
include '../../layouts/footer.php';
?>
