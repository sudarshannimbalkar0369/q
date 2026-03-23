<?php
require_once __DIR__ . '/includes/functions.php';
$slug = trim($_GET['slug'] ?? 'sci-fi');
$movies = get_all_movies($slug);
$pageTitle = 'MovieVerse AI | Category';
require_once __DIR__ . '/includes/header.php';
$selectedCategory = null;
foreach ($categories as $category) {
    if ($category['slug'] === $slug) {
        $selectedCategory = $category;
        break;
    }
}
?>
<section class="page-banner glass-panel" style="--accent: <?= htmlspecialchars($selectedCategory['accent_color'] ?? '#6d5dfc') ?>">
    <div>
        <span class="eyebrow">Genre Spotlight</span>
        <h1><?= htmlspecialchars($selectedCategory['name'] ?? 'Category') ?> Collection</h1>
        <p>Scroll through a deep grid of <?= htmlspecialchars($selectedCategory['name'] ?? 'movie') ?> recommendations with bold 3D hover motion.</p>
    </div>
</section>
<section class="section-block">
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <?php include __DIR__ . '/includes/movie-card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
