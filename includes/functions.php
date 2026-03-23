<?php
require_once __DIR__ . '/../db.php';

function get_categories(): array {
    global $conn;
    $result = $conn->query('SELECT * FROM categories ORDER BY name');
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function get_featured_movies(): array {
    global $conn;
    $sql = "SELECT m.*, c.name AS category_name, c.slug AS category_slug, c.accent_color, c.icon
            FROM movies m JOIN categories c ON c.id = m.category_id
            WHERE m.featured = 1 ORDER BY m.rating DESC, m.year_released DESC LIMIT 6";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function get_all_movies(?string $categorySlug = null, string $search = ''): array {
    global $conn;
    $sql = "SELECT m.*, c.name AS category_name, c.slug AS category_slug, c.accent_color, c.icon
            FROM movies m JOIN categories c ON c.id = m.category_id WHERE 1=1";
    $params = [];
    $types = '';
    if ($categorySlug) {
        $sql .= ' AND c.slug = ?';
        $params[] = $categorySlug;
        $types .= 's';
    }
    if ($search !== '') {
        $sql .= ' AND (m.title LIKE ? OR m.tagline LIKE ? OR m.cast_info LIKE ?)';
        $like = '%' . $search . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $types .= 'sss';
    }
    $sql .= ' ORDER BY m.featured DESC, m.rating DESC, m.year_released DESC';
    $stmt = $conn->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $movies = fetch_all_assoc($stmt);
    $stmt->close();
    return $movies;
}

function get_movie(int $id): ?array {
    global $conn;
    $stmt = $conn->prepare("SELECT m.*, c.name AS category_name, c.slug AS category_slug, c.accent_color, c.icon
        FROM movies m JOIN categories c ON c.id = m.category_id WHERE m.id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie = $result ? $result->fetch_assoc() : null;
    $stmt->close();
    return $movie ?: null;
}

function get_wishlist_movies(int $userId): array {
    global $conn;
    $stmt = $conn->prepare("SELECT m.*, c.name AS category_name, c.slug AS category_slug, c.accent_color, c.icon, w.created_at AS wished_at
        FROM wishlist w
        JOIN movies m ON m.id = w.movie_id
        JOIN categories c ON c.id = m.category_id
        WHERE w.user_id = ?
        ORDER BY w.created_at DESC");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $data = fetch_all_assoc($stmt);
    $stmt->close();
    return $data;
}

function in_wishlist(int $userId, int $movieId): bool {
    global $conn;
    $stmt = $conn->prepare('SELECT id FROM wishlist WHERE user_id = ? AND movie_id = ? LIMIT 1');
    $stmt->bind_param('ii', $userId, $movieId);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

function get_dashboard_stats(): array {
    global $conn;
    $stats = [];
    foreach ([
        'users' => 'SELECT COUNT(*) AS total FROM users WHERE role = "user"',
        'movies' => 'SELECT COUNT(*) AS total FROM movies',
        'wishlist' => 'SELECT COUNT(*) AS total FROM wishlist',
        'featured' => 'SELECT COUNT(*) AS total FROM movies WHERE featured = 1'
    ] as $key => $sql) {
        $result = $conn->query($sql);
        $row = $result ? $result->fetch_assoc() : ['total' => 0];
        $stats[$key] = (int)($row['total'] ?? 0);
    }
    return $stats;
}

function get_recent_logs(int $limit = 10): array {
    global $conn;
    $stmt = $conn->prepare("SELECT a.*, u.username FROM activity_logs a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.created_at DESC LIMIT ?");
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    $rows = fetch_all_assoc($stmt);
    $stmt->close();
    return $rows;
}

function require_login(): void {
    if (!is_logged_in()) {
        flash_set('warning', 'Please login first to continue.');
        redirect_to('login.php');
    }
}

function require_admin(): void {
    if (!is_admin()) {
        flash_set('danger', 'Admin access only.');
        redirect_to('../login.php');
    }
}
?>
