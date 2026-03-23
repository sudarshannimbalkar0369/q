<?php
require_once __DIR__ . '/includes/functions.php';
$stats = get_dashboard_stats();
$logs = get_recent_logs(12);
$pageTitle = 'MovieVerse AI | Reports';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-banner glass-panel">
    <div>
        <span class="eyebrow">Reports Overview</span>
        <h1>Platform insights and activity summary</h1>
    </div>
</section>
<section class="stats-grid">
    <article class="glass-panel"><strong><?= $stats['users'] ?></strong><span>Users</span></article>
    <article class="glass-panel"><strong><?= $stats['movies'] ?></strong><span>Movies</span></article>
    <article class="glass-panel"><strong><?= $stats['wishlist'] ?></strong><span>Wishlist Saves</span></article>
    <article class="glass-panel"><strong><?= $stats['featured'] ?></strong><span>Featured Picks</span></article>
</section>
<section class="table-section glass-panel">
    <h2>Recent Activity</h2>
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
<?php require_once __DIR__ . '/includes/footer.php'; ?>
