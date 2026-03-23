<?php
$pageTitle = 'MovieVerse AI | Home';
require_once __DIR__ . '/includes/header.php';
$featuredMovies = get_featured_movies();
$allMovies = get_all_movies();
$heroMovie = $featuredMovies[0] ?? ($allMovies[0] ?? null);
?>
<section class="hero-section" style="--hero-image: url('<?= htmlspecialchars($heroMovie['backdrop_url'] ?? 'https://images.unsplash.com/photo-1517602302552-471fe67acf66?auto=format&fit=crop&w=1600&q=80') ?>')">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="eyebrow">Ultra Cinematic · Black · Blue · Purple</div>
        <h1>Discover your next favorite movie with high-energy 3D animation.</h1>
        <p>Explore horror, thriller, romantic, sci-fi, action, fantasy, animation, and drama films in a futuristic recommendation website built with PHP, MySQL, HTML, CSS, and JavaScript for XAMPP.</p>
        <div class="hero-actions">
            <a class="primary-btn" href="movies.php">Browse Movies</a>
            <a class="ghost-btn" href="register.php">Create Account</a>
        </div>
        <div class="hero-stats">
            <article class="glass-panel"><strong><?= count($allMovies) ?>+</strong><span>Seeded Movies</span></article>
            <article class="glass-panel"><strong><?= count($categories) ?></strong><span>Genre Buttons</span></article>
            <article class="glass-panel"><strong>Auto</strong><span>DB + Table Setup</span></article>
        </div>
    </div>
    <div class="hero-visual">
        <?php if ($heroMovie): ?>
        <div class="floating-poster movie-card tilt-card" data-backdrop="<?= htmlspecialchars($heroMovie['backdrop_url']) ?>" data-teaser="<?= htmlspecialchars($heroMovie['teaser_url']) ?>">
            <img src="<?= htmlspecialchars($heroMovie['image_url']) ?>" alt="<?= htmlspecialchars($heroMovie['title']) ?>">
            <div class="movie-card__content">
                <span class="chip"><?= htmlspecialchars($heroMovie['category_name']) ?></span>
                <h3><?= htmlspecialchars($heroMovie['title']) ?></h3>
                <p><?= htmlspecialchars($heroMovie['tagline']) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="section-block">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Smart Categories</span>
            <h2>Category buttons crafted for fast mood-based discovery</h2>
        </div>
        <a class="ghost-btn" href="movies.php">See all movies</a>
    </div>
    <div class="category-grid">
        <?php foreach ($categories as $category): ?>
        <a class="category-card tilt-card" href="category.php?slug=<?= urlencode($category['slug']) ?>" style="--accent: <?= htmlspecialchars($category['accent_color']) ?>">
            <div class="category-card__icon"><?= htmlspecialchars($category['icon']) ?></div>
            <h3><?= htmlspecialchars($category['name']) ?></h3>
            <p>Explore hand-picked <?= htmlspecialchars($category['name']) ?> movies with immersive teaser previews.</p>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section-block">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Featured Picks</span>
            <h2>Cards change the page background while you scroll and hover</h2>
        </div>
    </div>
    <div class="movie-grid">
        <?php foreach ($featuredMovies as $movie): ?>
            <?php include __DIR__ . '/includes/movie-card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<section class="section-block split-layout">
    <div class="glass-panel info-panel">
        <span class="eyebrow">Built for XAMPP</span>
        <h2>Automatic database creation in <code>db.php</code>.</h2>
        <p>When you open the site on your XAMPP server, the application creates the <strong>movieverse</strong> database, tables, admin account, categories, and starter movies automatically.</p>
        <ul class="feature-list">
            <li>Admin panel to add and manage movies.</li>
            <li>User registration and login with wishlist support.</li>
            <li>Interactive teaser modal and hover preview panel.</li>
            <li>Reports pages for users, movies, wishlists, and logs.</li>
        </ul>
    </div>
    <div class="glass-panel preview-panel">
        <span class="eyebrow">Live Preview</span>
        <h3>Hover any card to change this teaser stage.</h3>
        <div class="preview-stage" id="previewStage" data-default-image="<?= htmlspecialchars($heroMovie['backdrop_url'] ?? '') ?>" style="background-image:url('<?= htmlspecialchars($heroMovie['backdrop_url'] ?? '') ?>')">
            <div class="preview-stage__overlay"></div>
            <div class="preview-stage__copy">
                <h4><?= htmlspecialchars($heroMovie['title'] ?? 'MovieVerse AI') ?></h4>
                <p><?= htmlspecialchars($heroMovie['tagline'] ?? 'Hover movie cards to preview details here.') ?></p>
                <button class="primary-btn teaser-launch" data-teaser="<?= htmlspecialchars($heroMovie['teaser_url'] ?? '') ?>">Play Teaser</button>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
