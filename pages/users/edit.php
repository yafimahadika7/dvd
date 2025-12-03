<?php
require_once '../../config/config.php';
cek_login();
cek_role(['admin']);

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Data tidak ditemukan");
}

$pageTitle = "Edit User";
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = $_POST['nama_lengkap'];
    $uname  = $_POST['username'];
    $role   = $_POST['role'];
    $pass   = $_POST['password'];

    if (!empty($pass)) {
        $update = $pdo->prepare("
            UPDATE users
            SET nama_lengkap = :nama_lengkap,
                username = :username,
                role = :role,
                password = :password
            WHERE id = :id
        ");
        $params = [
            'nama_lengkap' => $nama,
            'username'     => $uname,
            'role'         => $role,
            'password'     => $pass,
            'id'           => $id
        ];
    } else {
        $update = $pdo->prepare("
            UPDATE users
            SET nama_lengkap = :nama_lengkap,
                username = :username,
                role = :role
            WHERE id = :id
        ");
        $params = [
            'nama_lengkap' => $nama,
            'username'     => $uname,
            'role'         => $role,
            'id'           => $id
        ];
    }

    $update->execute($params);
    echo '<div class="alert alert-success">User berhasil diupdate.</div>';
}
?>

<h3>Edit User</h3>
<form method="post">
  <div class="mb-3">
    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" class="form-control" required
           value="<?= htmlspecialchars($user['nama_lengkap']) ?>">
  </div>
  <div class="mb-3">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required
           value="<?= htmlspecialchars($user['username']) ?>">
  </div>
  <div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-select" required>
      <option value="admin"   <?= $user['role']=='admin'?'selected':''; ?>>admin</option>
      <option value="petugas" <?= $user['role']=='petugas'?'selected':''; ?>>petugas</option>
      <option value="pemilik" <?= $user['role']=='pemilik'?'selected':''; ?>>pemilik</option>
      <option value="pelanggan" <?= $user['role']=='pelanggan'?'selected':''; ?>>pelanggan</option>
    </select>
  </div>
  <div class="mb-3">
    <label>Password (kosongkan jika tidak diubah)</label>
    <input type="password" name="password" class="form-control">
  </div>
  <button class="btn btn-primary">Update</button>
  <a href="<?= base_url('/pages/users/index.php') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php
include '../../layouts/footer.php';
?>
