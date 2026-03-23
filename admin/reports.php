<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$isAdmin = true;
$pageTitle = 'MovieVerse AI | Admin Reports';
$wishlistRows = $conn->query("SELECT u.username, m.title, w.created_at FROM wishlist w JOIN users u ON u.id = w.user_id JOIN movies m ON m.id = w.movie_id ORDER BY w.created_at DESC")->fetch_all(MYSQLI_ASSOC);
$movieRows = $conn->query("SELECT m.title, c.name AS category_name, m.rating, m.year_released, m.featured FROM movies m JOIN categories c ON c.id = m.category_id ORDER BY m.created_at DESC")->fetch_all(MYSQLI_ASSOC);
$logs = get_recent_logs(25);
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-banner glass-panel">
    <div>
        <span class="eyebrow">Reports Center</span>
        <h1>Movies, wishlists, and activity</h1>
    </div>
</section>
<section class="report-stack">
    <div class="table-section glass-panel">
        <h2>Movie Report</h2>
        <div class="table-wrap"><table><thead><tr><th>Title</th><th>Category</th><th>Rating</th><th>Year</th><th>Featured</th></tr></thead><tbody><?php foreach ($movieRows as $row): ?><tr><td><?= htmlspecialchars($row['title']) ?></td><td><?= htmlspecialchars($row['category_name']) ?></td><td><?= htmlspecialchars($row['rating']) ?></td><td><?= htmlspecialchars($row['year_released']) ?></td><td><?= $row['featured'] ? 'Yes' : 'No' ?></td></tr><?php endforeach; ?></tbody></table></div>
    </div>
    <div class="table-section glass-panel">
        <h2>Wishlist Report</h2>
        <div class="table-wrap"><table><thead><tr><th>User</th><th>Movie</th><th>Added At</th></tr></thead><tbody><?php foreach ($wishlistRows as $row): ?><tr><td><?= htmlspecialchars($row['username']) ?></td><td><?= htmlspecialchars($row['title']) ?></td><td><?= htmlspecialchars($row['created_at']) ?></td></tr><?php endforeach; ?></tbody></table></div>
    </div>
    <div class="table-section glass-panel">
        <h2>Activity Log Report</h2>
        <div class="table-wrap"><table><thead><tr><th>User</th><th>Action</th><th>Meta</th><th>Created</th></tr></thead><tbody><?php foreach ($logs as $log): ?><tr><td><?= htmlspecialchars($log['username'] ?? 'System') ?></td><td><?= htmlspecialchars($log['action_title']) ?></td><td><?= htmlspecialchars($log['action_meta'] ?? '-') ?></td><td><?= htmlspecialchars($log['created_at']) ?></td></tr><?php endforeach; ?></tbody></table></div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
