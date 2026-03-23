<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
$user = current_user();
if (isset($_GET['remove'])) {
    $movieId = (int)$_GET['remove'];
    $stmt = $conn->prepare('DELETE FROM wishlist WHERE user_id = ? AND movie_id = ?');
    $stmt->bind_param('ii', $user['id'], $movieId);
    $stmt->execute();
    $stmt->close();
    log_activity((int)$user['id'], 'Removed movie from wishlist', (string)$movieId);
    flash_set('success', 'Movie removed from wishlist.');
    redirect_to('wishlist.php');
}
$movies = get_wishlist_movies((int)$user['id']);
$pageTitle = 'MovieVerse AI | Wishlist';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-banner glass-panel">
    <div>
        <span class="eyebrow">My Wishlist</span>
        <h1><?= htmlspecialchars($user['name']) ?>'s saved movies</h1>
        <p>Keep your personal shortlist of teasers, stories, and future watch nights.</p>
    </div>
</section>
<section class="section-block">
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <div class="wishlist-wrap">
                <?php include __DIR__ . '/includes/movie-card.php'; ?>
                <a class="danger-link" href="wishlist.php?remove=<?= (int)$movie['id'] ?>">Remove</a>
            </div>
        <?php endforeach; ?>
        <?php if (!$movies): ?>
            <div class="empty-state glass-panel"><h3>Your wishlist is empty</h3><p>Open a movie and tap “Add to Wishlist”.</p></div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
