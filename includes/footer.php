    </main>
    <footer class="site-footer glass-panel">
        <div>
            <h3>MovieVerse AI</h3>
            <p>Smart recommendations, cinematic 3D visuals, teaser previews, and a complete XAMPP-ready movie portal.</p>
        </div>
        <div>
            <h4>Quick Pages</h4>
            <a href="<?= isset($isAdmin) && $isAdmin ? '../movies.php' : 'movies.php' ?>">Movies</a>
            <a href="<?= isset($isAdmin) && $isAdmin ? '../wishlist.php' : 'wishlist.php' ?>">Wishlist</a>
            <a href="<?= isset($isAdmin) && $isAdmin ? '../reports.php' : 'reports.php' ?>">Reports</a>
        </div>
        <div>
            <h4>Admin Login</h4>
            <p>Email: <strong>adi@gmail.com</strong></p>
            <p>Password: <strong>123</strong></p>
        </div>
    </footer>
</div>
<div class="teaser-modal" id="teaserModal">
    <div class="teaser-modal__backdrop"></div>
    <div class="teaser-modal__content glass-panel">
        <button class="teaser-modal__close">✕</button>
        <div class="teaser-modal__frame"></div>
    </div>
</div>
<script src="<?= isset($isAdmin) && $isAdmin ? '../assets/app.js' : 'assets/app.js' ?>"></script>
</body>
</html>
