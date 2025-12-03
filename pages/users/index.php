<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin']);

$pageTitle = "Kelola User";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    if ($id != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        echo '<div class="alert alert-success">User berhasil dihapus.</div>';
    } else {
        echo '<div class="alert alert-warning">Tidak bisa menghapus akun yang sedang digunakan.</div>';
    }
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Kelola User</h3>
  <a href="<?= base_url('/pages/users/tambah.php') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah User</a>
</div>

<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Username</th>
      <th>Role</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['role']) ?></td>
        <td>
          <a href="<?= base_url('/pages/users/edit.php?id=' . $row['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <?php if ($row['id'] != $_SESSION['user_id']) : ?>
          <a href="<?= base_url('/pages/users/index.php?hapus=' . $row['id']) ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Hapus user ini?')">Hapus</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php
include '../../layouts/footer.php';
?>
