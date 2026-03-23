<?php
require_once __DIR__ . '/includes/functions.php';
$movieId = (int)($_GET['id'] ?? 0);
$movie = get_movie($movieId);
if (!$movie) {
    flash_set('danger', 'Movie not found.');
    redirect_to('movies.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'wishlist') {
    require_login();
    $user = current_user();
    if (!in_wishlist((int)$user['id'], $movieId)) {
        $stmt = $conn->prepare('INSERT INTO wishlist (user_id, movie_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $user['id'], $movieId);
        $stmt->execute();
        $stmt->close();
        log_activity((int)$user['id'], 'Added movie to wishlist', $movie['title']);
        flash_set('success', 'Movie added to wishlist.');
    } else {
        flash_set('warning', 'Movie already exists in your wishlist.');
    }
    redirect_to('movie.php?id=' . $movieId);
}
$pageTitle = 'MovieVerse AI | ' . $movie['title'];
require_once __DIR__ . '/includes/header.php';
?>
<section class="movie-detail-hero" style="--hero-image: url('<?= htmlspecialchars($movie['backdrop_url']) ?>')">
    <div class="hero-overlay"></div>
    <div class="movie-detail-layout">
        <div class="movie-detail-poster tilt-card"><img src="<?= htmlspecialchars($movie['image_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>"></div>
        <div class="movie-detail-copy glass-panel">
            <span class="chip" style="--accent: <?= htmlspecialchars($movie['accent_color']) ?>"><?= htmlspecialchars($movie['category_name']) ?></span>
            <h1><?= htmlspecialchars($movie['title']) ?></h1>
            <h3><?= htmlspecialchars($movie['tagline']) ?></h3>
            <p><?= htmlspecialchars($movie['description']) ?></p>
            <div class="detail-meta-grid">
                <div><strong>Year</strong><span><?= htmlspecialchars($movie['year_released']) ?></span></div>
                <div><strong>Duration</strong><span><?= htmlspecialchars($movie['duration']) ?></span></div>
                <div><strong>Rating</strong><span>⭐ <?= htmlspecialchars($movie['rating']) ?></span></div>
                <div><strong>Director</strong><span><?= htmlspecialchars($movie['director']) ?></span></div>
                <div><strong>Cast</strong><span><?= htmlspecialchars($movie['cast_info']) ?></span></div>
            </div>
            <div class="hero-actions">
                <button class="primary-btn teaser-launch" data-teaser="<?= htmlspecialchars($movie['teaser_url']) ?>">Watch Teaser</button>
                <form method="post">
                    <input type="hidden" name="action" value="wishlist">
                    <button class="ghost-btn" type="submit">Add to Wishlist</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
