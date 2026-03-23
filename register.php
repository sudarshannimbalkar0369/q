<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $avatar = 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=300&q=80';
    if ($name && $username && $email && $password) {
        $stmt = $conn->prepare('INSERT INTO users (name, username, email, password, avatar) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssss', $name, $username, $email, $password, $avatar);
        if ($stmt->execute()) {
            log_activity((int)$stmt->insert_id, 'Registered account', $email);
            flash_set('success', 'Registration complete. Please login.');
            $stmt->close();
            redirect_to('login.php');
        }
        $stmt->close();
    }
    flash_set('danger', 'Could not register. Username or email may already exist.');
    redirect_to('register.php');
}
$pageTitle = 'MovieVerse AI | Register';
require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-shell">
    <form class="auth-card glass-panel" method="post">
        <span class="eyebrow">Create Account</span>
        <h1>Register as a movie lover</h1>
        <label>Full Name<input type="text" name="name" required></label>
        <label>Username<input type="text" name="username" required></label>
        <label>Email<input type="email" name="email" required></label>
        <label>Password<input type="password" name="password" required></label>
        <button class="primary-btn" type="submit">Register</button>
    </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
