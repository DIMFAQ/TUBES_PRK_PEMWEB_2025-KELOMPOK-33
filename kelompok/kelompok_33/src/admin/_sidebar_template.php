<!-- Sidebar Template - Include this in all admin pages -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-leaf"></i>
        </div>
        <div class="sidebar-brand">
            <h1>CleanSpot</h1>
            <p>Admin Panel</p>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'beranda_admin.php' ? 'active' : '' ?>">
            <a href="beranda_admin.php">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
        </div>
        <div class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'laporan_admin.php' ? 'active' : '' ?>">
            <a href="laporan_admin.php">
                <i class="fas fa-clipboard-list"></i>
                <span>Laporan</span>
            </a>
        </div>
        <div class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'pengguna_admin.php' ? 'active' : '' ?>">
            <a href="pengguna_admin.php">
                <i class="fas fa-users"></i>
                <span>Kelola Pengguna</span>
            </a>
        </div>
        <div class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'log_admin.php' ? 'active' : '' ?>">
            <a href="log_admin.php">
                <i class="fas fa-chart-line"></i>
                <span>Log Aktivitas</span>
            </a>
        </div>
    </nav>
    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar"><?= $initial ?? 'A' ?></div>
            <div class="user-info">
                <h4><?= htmlspecialchars($nama_admin ?? 'Admin') ?></h4>
                <p>admin@cleanspot.id</p>
            </div>
        </div>
        <a href="../auth/logout.php" class="btn btn-danger w-full mt-4">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
</div>