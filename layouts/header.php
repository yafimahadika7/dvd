<?php
if (!isset($pageTitle)) {
    $pageTitle = "Sistem Informasi Rental DVD";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #343a40; color: #fff; }
        .sidebar a { color: #fff; text-decoration: none; display: block; padding: 10px 15px; }
        .sidebar a:hover, .sidebar a.active { background: #495057; }
        .content { padding: 20px; }
        .card-stat { border-radius: 12px; }
        body {
        padding-bottom: 40px;
    }

    /* FOOTER STICKY */
    .app-footer {
        position: fixed;
        bottom: 0;
        left: 235px; /* mengikuti lebar sidebar */
        width: calc(100% - 165px);
        height: 32px;
        background: #ffffff;
        border-top: 1px solid #dee2e6;
        font-size: 12px;
        color: #777;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
    }
    </style>
</head>
<body>
