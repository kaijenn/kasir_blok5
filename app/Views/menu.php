<?php $currentUri = uri_string(); ?>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <img src="<?= base_url('images/' . $yogi->logo_website) ?>" alt="logo" style="max-width: 150%; height: auto; max-height: 100px;"/>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>

                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <!-- Dashboard -->
                        <!-- Dashboard -->
<li class="sidebar-item <?= ($currentUri == 'home/dashboard') ? 'active' : '' ?>">
    <a href="<?= base_url('home/dashboard') ?>" class='sidebar-link'>
        <i class="bi bi-house-door-fill"></i> <!-- Ikon untuk Dashboard -->
        <span>Dashboard</span>
    </a>
</li>

<!-- Barang -->
<li class="sidebar-item <?= ($currentUri == 'home/barang') ? 'active' : '' ?>">
    <a href="<?= base_url('home/barang') ?>" class='sidebar-link'>
        <i class="fa fa-box"></i> <!-- Ikon untuk Barang -->
        <span>Barang</span>
    </a>
</li>

<!-- Kasir -->
<li class="sidebar-item <?= ($currentUri == 'home/kasir') ? 'active' : '' ?>">
    <a href="<?= base_url('home/kasir') ?>" class='sidebar-link'>
        <i class="fa fa-cash-register"></i> <!-- Ikon untuk Kasir -->
        <span>Kasir</span>
    </a>
</li>

<!-- Barcode -->
<li class="sidebar-item <?= ($currentUri == 'home/barcode') ? 'active' : '' ?>">
    <a href="<?= base_url('home/barcode') ?>" class='sidebar-link'>
        <i class="fa fa-barcode"></i> <!-- Ikon untuk Barcode -->
        <span>Barcode</span>
    </a>
</li>

<!-- Member -->
<li class="sidebar-item <?= ($currentUri == 'home/member') ? 'active' : '' ?>">
    <a href="<?= base_url('home/member') ?>" class='sidebar-link'>
        <i class="fa fa-users"></i> <!-- Ikon untuk Member -->
        <span>Member</span>
    </a>
</li>

<!-- Settings -->
<li class="sidebar-item <?= ($currentUri == 'home/setting') ? 'active' : '' ?>">
    <a href="<?= base_url('home/setting') ?>" class='sidebar-link'>
        <i class="bi bi-gear-fill"></i> <!-- Ikon untuk Settings -->
        <span>Settings</span>
    </a>
</li>

<!-- Soft Delete -->
<li class="sidebar-item <?= ($currentUri == 'home/soft_delete') ? 'active' : '' ?>">
    <a href="<?= base_url('home/soft_delete') ?>" class='sidebar-link'>
        <i class="bi bi-trash-fill"></i> <!-- Ikon untuk Soft Delete -->
        <span>Soft Delete</span>
    </a>
</li>

<!-- Restore Edit -->
<li class="sidebar-item <?= ($currentUri == 'home/restore_edit_member') ? 'active' : '' ?>">
    <a href="<?= base_url('home/restore_edit_member') ?>" class='sidebar-link'>
        <i class="bi bi-arrow-counterclockwise"></i> <!-- Ikon untuk Restore -->
        <span>Restore Edit</span>
    </a>
</li>

<!-- Log Activity -->
<li class="sidebar-item <?= ($currentUri == 'home/log_activity') ? 'active' : '' ?>">
    <a href="<?= base_url('home/log_activity') ?>" class='sidebar-link'>
        <i class="bi bi-clipboard-data-fill"></i> <!-- Ikon untuk Log Activity -->
        <span>Log Activity</span>
    </a>
</li>

                    </ul>
                </div>
            </div>
        </div>
        <div id="main">
            <header class="mb-3"></header>
