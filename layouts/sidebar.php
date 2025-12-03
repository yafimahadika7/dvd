<?php
$role = $_SESSION['user_role'] ?? '';
$name = $_SESSION['user_name'] ?? 'User';

// foto default (bisa kamu ganti file-nya)
$avatarUrl = base_url('/static/assets/imgs/1.jpeg');

// helper buat kasih class "active" di menu
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function is_active(string $path, string $currentPath): string {
    return str_ends_with($currentPath, $path) ? 'active' : '';
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 sidebar p-0 d-flex flex-column">

      <!-- Brand + Profil User -->
      <div class="p-3 border-bottom" style="background: linear-gradient(135deg,#1e90ff,#00b894);">
        <div class="d-flex flex-column align-items-center text-center text-white">
          <div class="mb-2">
            <img src="<?= $avatarUrl ?>"
                 alt="Avatar"
                 class="rounded-circle"
                 style="width:70px;height:70px;object-fit:cover;border:2px solid rgba(255,255,255,.7);box-shadow:0 0 10px rgba(0,0,0,.3);">
          </div>
          <h5 class="mb-1"><?= htmlspecialchars($name) ?></h5>
          <span class="badge"
                style="background:rgba(0,0,0,.25);backdrop-filter:blur(4px);">
            <?= htmlspecialchars(strtoupper($role ?: 'TIDAK DIKETAHUI')) ?>
          </span>
        </div>
      </div>

      <!-- Menu -->
      <nav class="flex-grow-1 d-flex flex-column justify-content-between">
        <div class="pt-2">
          <?php if ($role === 'pelanggan'): ?>
            <a href="<?= base_url('/pages/portal/index.php') ?>"
               class="d-block px-3 py-2 <?= is_active('/pages/portal/index.php', $currentPath) ?>">
              <i class="bi bi-house-door me-2"></i> Beranda
            </a>
            <a href<?= "=\"" . base_url('/pages/portal/riwayat.php') . "\"" ?>
               class="d-block px-3 py-2 <?= is_active('/pages/portal/riwayat.php', $currentPath) ?>">
              <i class="bi bi-clock-history me-2"></i> Riwayat Sewa
            </a>
          <?php else: ?>
            <small class="text-uppercase text-light-50 px-3 mt-2 d-block" style="font-size:.7rem;opacity:.7;">
              Menu Utama
            </small>
            <a href="<?= base_url('/index.php') ?>"
               class="d-block px-3 py-2 <?= is_active('/index.php', $currentPath) ?>">
              <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="<?= base_url('/pages/dvd/index.php') ?>"
               class="d-block px-3 py-2 <?= is_active('/pages/dvd/index.php', $currentPath) ?>">
              <i class="bi bi-disc me-2"></i> Data DVD
            </a>
            <a href="<?= base_url('/pages/pelanggan/index.php') ?>"
               class="d-block px-3 py-2 <?= is_active('/pages/pelanggan/index.php', $currentPath) ?>">
              <i class="bi bi-people me-2"></i> Data Pelanggan
            </a>
            <a href="<?= base_url('/pages/transaksi/index.php') ?>"
               class="d-block px-3 py-2 <?= is_active('/pages/transaksi/index.php', $currentPath) ?>">
              <i class="bi bi-receipt me-2"></i> Transaksi Sewa
            </a>

            <?php if ($role === 'admin') : ?>
              <small class="text-uppercase text-light-50 px-3 mt-3 d-block" style="font-size:.7rem;opacity:.7;">
                Administrasi
              </small>
              <a href="<?= base_url('/pages/users/index.php') ?>"
                 class="d-block px-3 py-2 <?= is_active('/pages/users/index.php', $currentPath) ?>">
                <i class="bi bi-person-gear me-2"></i> Kelola User
              </a>
            <?php endif; ?>
          <?php endif; ?>
        </div>

        <!-- Tombol Logout di bawah -->
        <div class="pb-3 border-top mt-2">
          <a href="#" onclick="logoutConfirm()">
    <i class="bi bi-box-arrow-right me-2"></i> Logout
</a>

        </div>
      </nav>
    </div>

    <!-- konten -->
    <div class="col-md-10 content">
