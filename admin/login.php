<?php
require_once __DIR__ . '/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? AND password = ? AND role = "admin" LIMIT 1');
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $stmt->close();
    if ($user) {
        $_SESSION['user'] = $user;
        log_activity((int)$user['id'], 'Admin logged in', $email);
        flash_set('success', 'Admin access granted.');
        redirect_to('dashboard.php');
    }
    flash_set('danger', 'Invalid admin credentials. Use adi@gmail.com / 123.');
    redirect_to('login.php');
}
$pageTitle = 'MovieVerse AI | Admin Login';
$isAdmin = true;
require_once __DIR__ . '/../includes/header.php';
?>
<section class="auth-shell">
    <form class="auth-card glass-panel" method="post">
        <span class="eyebrow">Admin Login</span>
        <h1>Manage MovieVerse AI</h1>
        <label>Email<input type="email" name="email" value="adi@gmail.com" required></label>
        <label>Password<input type="password" name="password" value="123" required></label>
        <button class="primary-btn" type="submit">Login as Admin</button>
    </form>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
