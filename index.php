<?php
require_once 'config/config.php';
cek_login();

// kalau pelanggan, lempar ke portal
if (($_SESSION['user_role'] ?? '') === 'pelanggan') {
    header("Location: " . base_url('/pages/portal/index.php'));
    exit;
}

// ====== STATISTIK ATAS (KOTAK) ======
$totalDvd       = $pdo->query("SELECT COUNT(*) FROM dvd")->fetchColumn();
$totalPelanggan = $pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
$totalTransaksi = $pdo->query("SELECT COUNT(*) FROM transaksi_sewa")->fetchColumn();

// ====== GRAFIK 1: TRANSAKSI PER BULAN ======
$stmt = $pdo->query("
    SELECT DATE_FORMAT(tgl_sewa, '%Y-%m') AS bulan, COUNT(*) AS jumlah
    FROM transaksi_sewa
    GROUP BY DATE_FORMAT(tgl_sewa, '%Y-%m')
    ORDER BY bulan
");
$labelsBulan = [];
$dataTransaksi = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $labelsBulan[]   = $row['bulan'];
    $dataTransaksi[] = (int)$row['jumlah'];
}

// ====== GRAFIK 2: DISTRIBUSI DVD PER KATEGORI ======
$stmtKat = $pdo->query("
    SELECT 
        COALESCE(k.nama_kategori, 'Tanpa Kategori') AS nama_kategori,
        COUNT(d.id) AS jumlah
    FROM dvd d
    LEFT JOIN kategori_dvd k ON d.id_kategori = k.id
    GROUP BY k.id, k.nama_kategori
    ORDER BY nama_kategori
");
$labelsKategori = [];
$dataKategori   = [];
while ($row = $stmtKat->fetch(PDO::FETCH_ASSOC)) {
    $labelsKategori[] = $row['nama_kategori'];
    $dataKategori[]   = (int)$row['jumlah'];
}

// ====== GRAFIK 3: STATUS TRANSAKSI ======
$stmtStatus = $pdo->query("
    SELECT status, COUNT(*) AS jumlah
    FROM transaksi_sewa
    GROUP BY status
");
$labelsStatus = [];
$dataStatus   = [];
while ($row = $stmtStatus->fetch(PDO::FETCH_ASSOC)) {
    $labelsStatus[] = $row['status'] ?: 'tidak diketahui';
    $dataStatus[]   = (int)$row['jumlah'];
}

$pageTitle = "Dashboard - Rental DVD";
include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

<h3 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h3>

<!-- KOTAK STATISTIK -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="card card-stat shadow-sm border-0">
      <div class="card-body">
        <h6>Total DVD</h6>
        <h3><?= $totalDvd ?></h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-stat shadow-sm border-0">
      <div class="card-body">
        <h6>Total Pelanggan</h6>
        <h3><?= $totalPelanggan ?></h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-stat shadow-sm border-0">
      <div class="card-body">
        <h6>Total Transaksi</h6>
        <h3><?= $totalTransaksi ?></h3>
      </div>
    </div>
  </div>
</div>

<!-- LAYER 2: 3 GRAFIK -->
<div class="row">
  <!-- Grafik 1: Transaksi per Bulan -->
  <div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="card-title mb-3">
          <i class="bi bi-bar-chart"></i> Transaksi per Bulan
        </h6>
        <canvas id="chartTransaksi"></canvas>
      </div>
    </div>
  </div>

  <!-- Grafik 2: Distribusi DVD per Kategori -->
  <div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="card-title mb-3">
          <i class="bi bi-pie-chart"></i> DVD per Kategori
        </h6>
        <canvas id="chartKategori"></canvas>
      </div>
    </div>
  </div>

  <!-- Grafik 3: Status Transaksi -->
  <div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <h6 class="card-title mb-3">
          <i class="bi bi-clipboard-data"></i> Status Transaksi
        </h6>
        <canvas id="chartStatus"></canvas>
      </div>
    </div>
  </div>
</div>

<script>
// ====== DATA DARI PHP ======
const labelsBulan   = <?= json_encode($labelsBulan) ?>;
const dataTransaksi = <?= json_encode($dataTransaksi) ?>;

const labelsKategori = <?= json_encode($labelsKategori) ?>;
const dataKategori   = <?= json_encode($dataKategori) ?>;

const labelsStatus = <?= json_encode($labelsStatus) ?>;
const dataStatus   = <?= json_encode($dataStatus) ?>;

// ====== GRAFIK 1: BAR TRANSAKSI PER BULAN ======
const ctx1 = document.getElementById('chartTransaksi').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: labelsBulan,
        datasets: [{
            label: 'Jumlah Transaksi',
            data: dataTransaksi,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0 }
            }
        }
    }
});

// ====== GRAFIK 2: DOUGHNUT DVD PER KATEGORI ======
const ctx2 = document.getElementById('chartKategori').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: labelsKategori,
        datasets: [{
            label: 'Jumlah DVD',
            data: dataKategori,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// ====== GRAFIK 3: PIE STATUS TRANSAKSI ======
const ctx3 = document.getElementById('chartStatus').getContext('2d');
new Chart(ctx3, {
    type: 'pie',
    data: {
        labels: labelsStatus,
        datasets: [{
            label: 'Jumlah Transaksi',
            data: dataStatus,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>

<?php
include 'layouts/footer.php';
?>
