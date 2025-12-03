<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin']);

$pageTitle = "Tambah User";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = $_POST['nama_lengkap'];
    $user   = $_POST['username'];
    $pass   = $_POST['password'];
    $role   = $_POST['role'];

    $stmt = $pdo->prepare("
        INSERT INTO users (nama_lengkap, username, password, role)
        VALUES (:nama_lengkap, :username, :password, :role)
    ");
    $stmt->execute([
        'nama_lengkap' => $nama,
        'username'     => $user,
        'password'     => $pass,
        'role'         => $role
    ]);

    echo '<div class="alert alert-success">User berhasil disimpan.</div>';
}
?>

<h3>Tambah User</h3>
<form method="post">
  <div class="mb-3">
    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-select" required>
      <option value="admin">admin</option>
      <option value="petugas">petugas</option>
      <option value="pemilik">pemilik</option>
      <option value="pelanggan">pelanggan</option>
    </select>
  </div>
  <button class="btn btn-primary">Simpan</button>
  <a href="<?= base_url('/pages/users/index.php') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php
include '../../layouts/footer.php';
?>
