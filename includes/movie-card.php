<article class="movie-card tilt-card" data-backdrop="<?= htmlspecialchars($movie['backdrop_url']) ?>" data-title="<?= htmlspecialchars($movie['title']) ?>" data-tagline="<?= htmlspecialchars($movie['tagline']) ?>" data-teaser="<?= htmlspecialchars($movie['teaser_url']) ?>">
    <div class="movie-card__image-wrap">
        <img src="<?= htmlspecialchars($movie['image_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
        <div class="movie-card__hover">
            <button class="primary-btn teaser-launch" data-teaser="<?= htmlspecialchars($movie['teaser_url']) ?>">Watch Teaser</button>
            <a class="ghost-btn" href="movie.php?id=<?= (int)$movie['id'] ?>">Details</a>
        </div>
    </div>
    <div class="movie-card__content">
        <div class="movie-meta-row">
            <span class="chip" style="--accent: <?= htmlspecialchars($movie['accent_color']) ?>"><?= htmlspecialchars($movie['category_name']) ?></span>
            <span>⭐ <?= htmlspecialchars($movie['rating']) ?></span>
        </div>
        <h3><?= htmlspecialchars($movie['title']) ?></h3>
        <p><?= htmlspecialchars($movie['tagline']) ?></p>
        <div class="movie-meta-row movie-meta-row--small">
            <span><?= htmlspecialchars($movie['year_released']) ?></span>
            <span><?= htmlspecialchars($movie['duration']) ?></span>
        </div>
    </div>
</article>
