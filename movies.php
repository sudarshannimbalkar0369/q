<?php
$pageTitle = 'MovieVerse AI | Movies';
require_once __DIR__ . '/includes/header.php';
$search = trim($_GET['search'] ?? '');
$categorySlug = trim($_GET['category'] ?? '');
$movies = get_all_movies($categorySlug ?: null, $search);
?>
<section class="page-banner glass-panel">
    <div>
        <span class="eyebrow">Movie Library</span>
        <h1>Explore the full recommendation collection</h1>
        <p>Search by title, tagline, or cast and jump into beautifully animated movie cards.</p>
    </div>
    <form class="search-bar" method="get">
        <input type="text" name="search" placeholder="Search movie, cast, tagline..." value="<?= htmlspecialchars($search) ?>">
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['slug']) ?>" <?= $categorySlug === $category['slug'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="primary-btn" type="submit">Filter</button>
    </form>
</section>
<section class="section-block">
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <?php include __DIR__ . '/includes/movie-card.php'; ?>
        <?php endforeach; ?>
        <?php if (!$movies): ?>
            <div class="empty-state glass-panel"><h3>No movies found</h3><p>Try another search or category.</p></div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
