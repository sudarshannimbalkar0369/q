<?php
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'movieverse';

mysqli_report(MYSQLI_REPORT_OFF);

function db_connect_server(): mysqli {
    global $DB_HOST, $DB_USER, $DB_PASS;
    $conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS);
    if ($conn->connect_error) {
        die('MySQL connection failed: ' . $conn->connect_error . '. Start Apache and MySQL in XAMPP, then reload this page.');
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

function db_bootstrap(mysqli $server): void {
    global $DB_NAME;
    $server->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $server->select_db($DB_NAME);

    $queries = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(120) NOT NULL,
            username VARCHAR(80) NOT NULL UNIQUE,
            email VARCHAR(120) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('user','admin') NOT NULL DEFAULT 'user',
            avatar VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(80) NOT NULL UNIQUE,
            name VARCHAR(80) NOT NULL,
            accent_color VARCHAR(20) DEFAULT '#6d5dfc',
            icon VARCHAR(10) DEFAULT '🎬'
        ) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS movies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT NOT NULL,
            title VARCHAR(160) NOT NULL,
            tagline VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            year_released INT NOT NULL,
            duration VARCHAR(30) NOT NULL,
            rating DECIMAL(3,1) NOT NULL DEFAULT 0,
            image_url TEXT NOT NULL,
            backdrop_url TEXT NOT NULL,
            teaser_url TEXT NOT NULL,
            director VARCHAR(120) NOT NULL,
            cast_info TEXT NOT NULL,
            featured TINYINT(1) NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        ) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS wishlist (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            movie_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_wishlist (user_id, movie_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
        ) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT DEFAULT NULL,
            action_title VARCHAR(180) NOT NULL,
            action_meta TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB"
    ];

    foreach ($queries as $sql) {
        if (!$server->query($sql)) {
            die('Database setup error: ' . $server->error);
        }
    }

    $server->query("INSERT INTO users (name, username, email, password, role, avatar)
        SELECT 'Adi Admin', 'adi_admin', 'adi@gmail.com', '123', 'admin', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80'
        FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='adi@gmail.com')");

    $categories = [
        ['horror', 'Horror', '#f72585', '👻'],
        ['thriller', 'Thriller', '#7209b7', '🔪'],
        ['romantic', 'Romantic', '#ff6bcb', '💜'],
        ['sci-fi', 'Sci-Fi', '#4cc9f0', '🚀'],
        ['action', 'Action', '#4895ef', '🔥'],
        ['fantasy', 'Fantasy', '#560bad', '🧙'],
        ['animation', 'Animation', '#3a86ff', '✨'],
        ['drama', 'Drama', '#8338ec', '🎭']
    ];

    $stmtCat = $server->prepare("INSERT IGNORE INTO categories (slug, name, accent_color, icon) VALUES (?, ?, ?, ?)");
    foreach ($categories as $cat) {
        $stmtCat->bind_param('ssss', $cat[0], $cat[1], $cat[2], $cat[3]);
        $stmtCat->execute();
    }
    $stmtCat->close();

    $movieSeeds = [
        ['sci-fi', 'Nebula Drift', 'A cosmic odyssey through collapsing galaxies.', 'When a rogue navigator discovers a time-folding map, she must outrun a sentient black hole and decode memories hidden in starlight.', 2026, '2h 11m', 9.1, 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/7Eeo-82Eac8?autoplay=1&mute=1', 'Lina Voss', 'Ari Chen, Milo Kane, Zuri West', 1],
        ['horror', 'Midnight Manor', 'Every hallway remembers your fear.', 'A paranormal podcaster broadcasts from an abandoned estate and discovers the house edits reality whenever the clock strikes midnight.', 2025, '1h 58m', 8.7, 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/FnCdOQsX5kc?autoplay=1&mute=1', 'Marta Velez', 'Nora Bloom, Ethan Vale, Kara Mills', 1],
        ['thriller', 'Cipher Run', 'Trust nobody. Decode everything.', 'An ethical hacker gets trapped in a global conspiracy after decrypting a surveillance archive linked to world leaders.', 2024, '2h 06m', 8.8, 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/hA6hldpSTF8?autoplay=1&mute=1', 'Gavin Price', 'Theo Hart, Sia Monroe, Idris Lane', 1],
        ['romantic', 'Moonlit Letters', 'Love arrives in the spaces between words.', 'Two strangers begin exchanging anonymous letters in a midnight library and slowly fall in love before learning their families are rivals.', 2023, '1h 49m', 8.4, 'https://images.unsplash.com/photo-1516589178581-6cd7833ae3b2?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/8ugaeA-nMTc?autoplay=1&mute=1', 'Elise Morgan', 'Mia Stone, Oliver Reed, Selene Park', 0],
        ['action', 'Velocity Zone', 'Speed is the only language left.', 'A retired street racer returns to the neon underworld to dismantle an armored syndicate using hypercars and hacked highways.', 2025, '2h 02m', 8.2, 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/M7XM597XO94?autoplay=1&mute=1', 'Rex Donovan', 'Jade Pierce, Axel Nori, Cam Fox', 0],
        ['fantasy', 'Crystal Kingdom', 'Magic awakens where the sky touches ice.', 'An apprentice cartographer must unite dragon clans and sky sailors to save a shattered realm of floating glaciers.', 2024, '2h 17m', 8.9, 'https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1519608487953-e999c86e7455?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/k0B4oY1wzjw?autoplay=1&mute=1', 'Amara Dune', 'Lyra Quinn, Ben Arlo, Cass Vane', 1],
        ['animation', 'Pixel Parade', 'Imagination hits maximum color.', 'A young coder and her sketched fox dive into a living arcade where every level rewrites the real world.', 2022, '1h 42m', 8.0, 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1518773553398-650c184e0bb3?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/TI_jq6BS1HU?autoplay=1&mute=1', 'Niko Vale', 'Poppy Ray, Iven Cruz, Dax Hale', 0],
        ['drama', 'Glass Hearts', 'Some truths glow brightest in the dark.', 'An ambitious violinist rebuilds her life after a public scandal by mentoring gifted teens in a storm-battered coastal town.', 2023, '2h 01m', 8.3, 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/gCcx85zbxz4?autoplay=1&mute=1', 'Helena Frost', 'Eva North, Liam Shore, Tessa Glenn', 0],
        ['sci-fi', 'Quantum Echo', 'Tomorrow is listening.', 'A sound engineer receives radio signals from future Earth and races to prevent a planetary blackout.', 2021, '1h 55m', 8.6, 'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/XHk5kCIiGoM?autoplay=1&mute=1', 'Tariq Wells', 'Nova Blake, Ian Korr, Freya Moon', 0],
        ['thriller', 'Silent Frequency', 'The message was never meant to be heard.', 'A radio host uncovers a coded emergency call tied to a string of disappearances across a sleepless city.', 2022, '1h 47m', 8.1, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/zSWdZVtXT7E?autoplay=1&mute=1', 'Jon Mercer', 'Elle Kane, Ryan Frost, Mira Cole', 0],
        ['romantic', 'Paris in Purple', 'Fate paints in violet.', 'A mural artist and a jazz pianist rediscover color, music, and intimacy while renovating a forgotten theater.', 2024, '1h 53m', 8.5, 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/5PSNL1qE6VY?autoplay=1&mute=1', 'Camille Rowe', 'Aria Bell, Nico James, Fleur Dean', 0],
        ['horror', 'Shadow Bloom', 'Beauty grows where terror feeds.', 'A botanist studying a bioluminescent forest realizes the flowers bloom only after visitors vanish.', 2026, '1h 51m', 8.9, 'https://images.unsplash.com/photo-1503435824048-a799a3a84bf7?auto=format&fit=crop&w=900&q=80', 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/s7EdQ4FqbhY?autoplay=1&mute=1', 'Iris Vale', 'Nina Row, Cole Mercer, Dana Pike', 1]
    ];

    $stmtMovie = $server->prepare("INSERT INTO movies (category_id, title, tagline, description, year_released, duration, rating, image_url, backdrop_url, teaser_url, director, cast_info, featured)
        SELECT c.id, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? FROM categories c WHERE c.slug = ?");
    foreach ($movieSeeds as $m) {
        $slug = $m[0];
        $existsStmt = $server->prepare("SELECT id FROM movies WHERE title = ? LIMIT 1");
        $existsStmt->bind_param('s', $m[1]);
        $existsStmt->execute();
        $existsStmt->store_result();
        if ($existsStmt->num_rows === 0) {
            $stmtMovie->bind_param('sssisdsssssis', $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $slug);
            $stmtMovie->execute();
        }
        $existsStmt->close();
    }
    $stmtMovie->close();
}

$conn = db_connect_server();
db_bootstrap($conn);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool {
    return isset($_SESSION['user']);
}

function is_admin(): bool {
    return is_logged_in() && ($_SESSION['user']['role'] ?? '') === 'admin';
}

function redirect_to(string $path): void {
    header('Location: ' . $path);
    exit;
}

function flash_set(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array {
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function log_activity(?int $userId, string $title, ?string $meta = null): void {
    global $conn;
    $stmt = $conn->prepare('INSERT INTO activity_logs (user_id, action_title, action_meta) VALUES (?, ?, ?)');
    $stmt->bind_param('iss', $userId, $title, $meta);
    $stmt->execute();
    $stmt->close();
}

function fetch_all_assoc(mysqli_stmt $stmt): array {
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
?>
