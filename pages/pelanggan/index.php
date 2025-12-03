<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin','petugas','pemilik']);

$pageTitle = "Data Pelanggan";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if (isset($_GET['hapus']) && $_SESSION['user_role'] !== 'pemilik') {
    $id = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM pelanggan WHERE id = :id");
    $stmt->execute(['id' => $id]);
    echo '<div class="alert alert-success">Data pelanggan berhasil dihapus.</div>';
}

$stmt = $pdo->query("
    SELECT p.*, u.username 
    FROM pelanggan p
    LEFT JOIN users u ON p.user_id = u.id
    ORDER BY p.id DESC
");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Data Pelanggan</h3>
  <?php if ($_SESSION['user_role'] !== 'pemilik') : ?>
    <a href="<?= base_url('/pages/pelanggan/tambah.php') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Pelanggan</a>
  <?php endif; ?>
</div>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Alamat</th>
      <th>Telepon</th>
      <th>Email</th>
      <th>User Login</th>
      <?php if ($_SESSION['user_role'] !== 'pemilik') : ?>
      <th>Aksi</th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td><?= htmlspecialchars($row['telepon']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
        <?php if ($_SESSION['user_role'] !== 'pemilik') : ?>
        <td>
          <a href="<?= base_url('/pages/pelanggan/edit.php?id=' . $row['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="<?= base_url('/pages/pelanggan/index.php?hapus=' . $row['id']) ?>" class="btn btn-sm btn-danger"
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
