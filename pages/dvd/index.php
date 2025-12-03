<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin','petugas','pemilik']);

$pageTitle = "Data DVD";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if (isset($_GET['hapus']) && $_SESSION['user_role'] !== 'pemilik') {
    $id = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM dvd WHERE id = :id");
    $stmt->execute(['id' => $id]);
    echo '<div class="alert alert-success">Data DVD berhasil dihapus.</div>';
}

$stmt = $pdo->query("
    SELECT dvd.*, kategori_dvd.nama_kategori 
    FROM dvd 
    LEFT JOIN kategori_dvd ON dvd.id_kategori = kategori_dvd.id
    ORDER BY dvd.id DESC
");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Data DVD</h3>
  <?php if ($_SESSION['user_role'] !== 'pemilik') : ?>
    <a href="<?= base_url('/pages/dvd/tambah.php') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah DVD</a>
  <?php endif; ?>
</div>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Judul</th>
      <th>Kategori</th>
      <th>Tahun</th>
      <th>Stok</th>
      <th>Harga Sewa</th>
      <?php if ($_SESSION['user_role'] !== 'pemilik') : ?>
      <th>Aksi</th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['judul']) ?></td>
        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
        <td><?= htmlspecialchars($row['tahun_rilis']) ?></td>
        <td><?= htmlspecialchars($row['stok']) ?></td>
        <td><?= number_format($row['harga_sewa'], 0, ',', '.') ?></td>
        <?php if ($_SESSION['user_role'] !== 'pemilik') : ?>
        <td>
          <a href="<?= base_url('/pages/dvd/edit.php?id=' . $row['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="<?= base_url('/pages/dvd/index.php?hapus=' . $row['id']) ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
        <?php endif; ?>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php
include '../../layouts/footer.php';
?>
