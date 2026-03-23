<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$isAdmin = true;
$pageTitle = 'MovieVerse AI | Admin Dashboard';
$stats = get_dashboard_stats();
$logs = get_recent_logs(8);
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-banner glass-panel">
    <div>
        <span class="eyebrow">Admin Dashboard</span>
        <h1>Command center for movies, users, and reports</h1>
        <p>Use the buttons below to manage the platform.</p>
    </div>
    <div class="admin-quick-links">
        <a class="primary-btn" href="movies.php">Manage Movies</a>
        <a class="ghost-btn" href="users.php">View Users</a>
        <a class="ghost-btn" href="reports.php">View Reports</a>
    </div>
</section>
<section class="stats-grid">
    <article class="glass-panel"><strong><?= $stats['users'] ?></strong><span>Registered Users</span></article>
    <article class="glass-panel"><strong><?= $stats['movies'] ?></strong><span>Total Movies</span></article>
    <article class="glass-panel"><strong><?= $stats['wishlist'] ?></strong><span>Wishlist Items</span></article>
    <article class="glass-panel"><strong><?= $stats['featured'] ?></strong><span>Featured Movies</span></article>
</section>
<section class="table-section glass-panel">
    <h2>Recent Logs</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>User</th><th>Action</th><th>Meta</th><th>Time</th></tr></thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr><td><?= htmlspecialchars($log['username'] ?? 'System') ?></td><td><?= htmlspecialchars($log['action_title']) ?></td><td><?= htmlspecialchars($log['action_meta'] ?? '-') ?></td><td><?= htmlspecialchars($log['created_at']) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
