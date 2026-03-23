<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = trim($_POST['identity'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $stmt = $conn->prepare('SELECT * FROM users WHERE (email = ? OR username = ?) AND password = ? LIMIT 1');
    $stmt->bind_param('sss', $identity, $identity, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $stmt->close();
    if ($user) {
        $_SESSION['user'] = $user;
        log_activity((int)$user['id'], 'Logged in', $user['email']);
        flash_set('success', 'Welcome back, ' . $user['name'] . '!');
        redirect_to($user['role'] === 'admin' ? 'admin/dashboard.php' : 'index.php');
    }
    flash_set('danger', 'Invalid login credentials.');
    redirect_to('login.php');
}
$pageTitle = 'MovieVerse AI | Login';
require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-shell">
    <form class="auth-card glass-panel" method="post">
        <span class="eyebrow">User Login</span>
        <h1>Login to save your wishlist</h1>
        <label>Email or Username<input type="text" name="identity" required></label>
        <label>Password<input type="password" name="password" required></label>
        <button class="primary-btn" type="submit">Login</button>
        <p>New user? <a href="register.php">Register here</a>.</p>
    </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
