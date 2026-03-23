<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $tagline = trim($_POST['tagline'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $year = (int)($_POST['year_released'] ?? date('Y'));
    $duration = trim($_POST['duration'] ?? '2h 00m');
    $rating = (float)($_POST['rating'] ?? 8.0);
    $image = trim($_POST['image_url'] ?? '');
    $backdrop = trim($_POST['backdrop_url'] ?? '');
    $teaser = trim($_POST['teaser_url'] ?? '');
    $director = trim($_POST['director'] ?? 'Unknown');
    $cast = trim($_POST['cast_info'] ?? 'Cast TBD');
    $featured = isset($_POST['featured']) ? 1 : 0;
    $stmt = $conn->prepare('INSERT INTO movies (category_id, title, tagline, description, year_released, duration, rating, image_url, backdrop_url, teaser_url, director, cast_info, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('isssisdsssssi', $categoryId, $title, $tagline, $description, $year, $duration, $rating, $image, $backdrop, $teaser, $director, $cast, $featured);
    if ($stmt->execute()) {
        log_activity((int)current_user()['id'], 'Added movie', $title);
        flash_set('success', 'Movie added successfully.');
    } else {
        flash_set('danger', 'Could not add movie.');
    }
    $stmt->close();
    redirect_to('movies.php');
}
if (isset($_GET['delete'])) {
    $movieId = (int)$_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM movies WHERE id = ?');
    $stmt->bind_param('i', $movieId);
    $stmt->execute();
    $stmt->close();
    log_activity((int)current_user()['id'], 'Deleted movie', (string)$movieId);
    flash_set('success', 'Movie deleted successfully.');
    redirect_to('movies.php');
}
$movies = get_all_movies();
$isAdmin = true;
$pageTitle = 'MovieVerse AI | Admin Movies';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-banner glass-panel">
    <div>
        <span class="eyebrow">Movie Management</span>
        <h1>Add new online-image movie cards and teasers</h1>
    </div>
</section>
<section class="admin-grid">
    <form class="glass-panel admin-form" method="post">
        <h2>Add Movie</h2>
        <label>Category
            <select name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Title<input type="text" name="title" required></label>
        <label>Tagline<input type="text" name="tagline" required></label>
        <label>Description<textarea name="description" rows="5" required></textarea></label>
        <div class="form-row">
            <label>Year<input type="number" name="year_released" value="<?= date('Y') ?>" required></label>
            <label>Duration<input type="text" name="duration" value="2h 00m" required></label>
            <label>Rating<input type="number" step="0.1" name="rating" value="8.0" required></label>
        </div>
        <label>Poster Image URL<input type="url" name="image_url" required></label>
        <label>Backdrop Image URL<input type="url" name="backdrop_url" required></label>
        <label>YouTube Embed Teaser URL<input type="url" name="teaser_url" required></label>
        <label>Director<input type="text" name="director" required></label>
        <label>Cast<input type="text" name="cast_info" required></label>
        <label class="checkbox-row"><input type="checkbox" name="featured" value="1"> Mark as featured</label>
        <button class="primary-btn" type="submit">Add Movie</button>
    </form>
    <div class="glass-panel table-section">
        <h2>Movie List</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Movie</th><th>Category</th><th>Rating</th><th>Year</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td><?= htmlspecialchars($movie['title']) ?></td>
                            <td><?= htmlspecialchars($movie['category_name']) ?></td>
                            <td><?= htmlspecialchars($movie['rating']) ?></td>
                            <td><?= htmlspecialchars($movie['year_released']) ?></td>
                            <td><a class="danger-link" href="movies.php?delete=<?= (int)$movie['id'] ?>">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
