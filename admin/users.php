<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$isAdmin = true;
$pageTitle = 'MovieVerse AI | Admin Users';
$users = $conn->query('SELECT * FROM users ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-banner glass-panel">
    <div>
        <span class="eyebrow">User Report</span>
        <h1>Registered user details</h1>
    </div>
</section>
<section class="table-section glass-panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr><td><?= htmlspecialchars($user['name']) ?></td><td><?= htmlspecialchars($user['username']) ?></td><td><?= htmlspecialchars($user['email']) ?></td><td><?= htmlspecialchars($user['role']) ?></td><td><?= htmlspecialchars($user['created_at']) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
