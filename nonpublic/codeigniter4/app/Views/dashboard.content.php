<!-- File: app/Views/dashboard_content.php -->
<?= $this->extend('layouts/main_layout'); ?>
<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <!-- Bagian Daftar Peserta Pelatihan -->
    <?php include "tabel.php"; ?>
</div>
<?= $this->endSection() ?>