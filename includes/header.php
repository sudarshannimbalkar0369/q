<?php
require_once __DIR__ . '/functions.php';
$categories = get_categories();
$flash = flash_get();
$currentPath = basename($_SERVER['PHP_SELF']);
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'MovieVerse AI') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= isset($isAdmin) && $isAdmin ? '../assets/style.css' : 'assets/style.css' ?>">
</head>
<body>
<div class="page-shell">
    <div class="bg-orb orb-one"></div>
    <div class="bg-orb orb-two"></div>
    <div class="bg-grid"></div>
    <header class="site-header glass-panel">
        <a class="brand" href="<?= isset($isAdmin) && $isAdmin ? '../index.php' : 'index.php' ?>">
            <span class="brand-mark">MV</span>
            <div>
                <strong>MovieVerse AI</strong>
                <small>3D Movie Recommendation Universe</small>
            </div>
        </a>
        <nav class="main-nav">
            <a class="<?= $currentPath === 'index.php' ? 'active' : '' ?>" href="<?= isset($isAdmin) && $isAdmin ? '../index.php' : 'index.php' ?>">Home</a>
            <a class="<?= $currentPath === 'movies.php' ? 'active' : '' ?>" href="<?= isset($isAdmin) && $isAdmin ? '../movies.php' : 'movies.php' ?>">Movies</a>
            <a class="<?= $currentPath === 'wishlist.php' ? 'active' : '' ?>" href="<?= isset($isAdmin) && $isAdmin ? '../wishlist.php' : 'wishlist.php' ?>">Wishlist</a>
            <?php if ($user && ($user['role'] ?? '') === 'admin' && !(isset($isAdmin) && $isAdmin)): ?>
                <a href="admin/dashboard.php">Admin Panel</a>
            <?php endif; ?>
            <div class="nav-dropdown">
                <button class="ghost-btn nav-dropdown-toggle">Login ▾</button>
                <div class="nav-dropdown-menu glass-panel">
                    <?php if ($user): ?>
                        <a href="<?= isset($isAdmin) && $isAdmin ? '../logout.php' : 'logout.php' ?>">Logout</a>
                    <?php else: ?>
                        <a href="<?= isset($isAdmin) && $isAdmin ? '../login.php' : 'login.php' ?>">User Login</a>
                        <a href="<?= isset($isAdmin) && $isAdmin ? '../register.php' : 'register.php' ?>">Register</a>
                        <a href="<?= isset($isAdmin) && $isAdmin ? '../admin/login.php' : 'admin/login.php' ?>">Admin Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <button class="mobile-nav-toggle">☰</button>
    </header>
    <aside class="category-ribbon glass-panel">
        <?php foreach ($categories as $category): ?>
            <a href="<?= isset($isAdmin) && $isAdmin ? '../category.php?slug=' : 'category.php?slug=' ?><?= urlencode($category['slug']) ?>" style="--accent: <?= htmlspecialchars($category['accent_color']) ?>">
                <span><?= htmlspecialchars($category['icon']) ?></span><?= htmlspecialchars($category['name']) ?>
            </a>
        <?php endforeach; ?>
    </aside>
    <main>
        <?php if ($flash): ?>
            <div class="flash flash-<?= htmlspecialchars($flash['type']) ?> glass-panel"><?= htmlspecialchars($flash['message']) ?></div>
        <?php endif; ?>
