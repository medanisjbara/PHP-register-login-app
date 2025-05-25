<?php
require_once 'classes/User.php';
require_once 'classes/Input.php';
require_once 'classes/Redirect.php';
require_once 'classes/DB.php';
require_once 'classes/Cookies.php';

session_start();
if (!isset($_COOKIE['user'])) {
    Redirect::go('login.php');
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Get current user id using the Cookie class (project convention)
$cookie = new Cookie();
$userId = $cookie->getId();

$db = DB::getInstance();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userArr = $db->getData("*", "users", ["id", "=", $userId]);
    $user = $userArr ? $userArr[0] : null;
    if ($user && isset($user['is_seller']) && $user['is_seller']) {
        $message = 'You are already a seller!';
    } else {
        $db->updateData('users', ['is_seller' => 1], $userId);
        $message = 'Congratulations! You are now a seller.';
    }
}

// Always fetch fresh user data to determine status
$userArr = $db->getData("*", "users", ["id", "=", $userId]);
$user = $userArr ? $userArr[0] : null;

// Navigation and categories (for header/footer)
$categories = [
    "Programming & Tech", "Graphics & Design", "Digital Marketing",
    "Writing & Translation", "Video & Animation", "AI Services",
    "Music & Audio", "Business", "Consulting"
];
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Become a Seller</title>
    <link rel="stylesheet" href="<?= e($baseUrl) ?>styles/index.css" />
</head>
<body>

<header>
    <nav>
        <div class="nav-left">
            <a href="<?= e($baseUrl) ?>become_seller.php" aria-current="page">Become a Seller</a>
            <a href="<?= e($baseUrl) ?>fiverr_go.php">Fiverr Go</a>
        </div>
        <div class="nav-categories" aria-label="Main categories">
            <?php foreach ($categories as $category): ?>
                <a href="<?= e($baseUrl) ?>category.php?name=<?= urlencode($category) ?>"><?= e($category) ?></a>
            <?php endforeach; ?>
        </div>
        <div class="nav-right">
            <a href="<?= e($baseUrl) ?>dashboard.php">Dashboard</a>
            <a href="<?= e($baseUrl) ?>logout.php">Logout</a>
        </div>
    </nav>
</header>

<section class="container" style="max-width: 500px; margin: 60px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); padding: 40px 24px; text-align: center;">
    <h2 style="margin-bottom: 24px; color:#1dbf73;">Become a Seller</h2>
    <?php if ($message): ?>
        <div style="margin-bottom: 24px; color:<?= strpos($message, 'Congratulations') !== false ? '#1dbf73' : '#dc3545' ?>; font-weight:600;">
            <?= e($message) ?>
        </div>
    <?php endif; ?>
    <?php if ($user && $user['is_seller']): ?>
        <div style="color:#1dbf73; font-weight:600;">You are already a seller!</div>
    <?php else: ?>
        <form method="post">
            <p style="margin-bottom: 24px;">Click below to become a seller and unlock seller features instantly!</p>
            <button type="submit" style="padding: 12px 32px; background: #1dbf73; color: #fff; font-size: 1.1rem; font-weight: 600; border: none; border-radius: 5px; cursor:pointer; transition: background 0.3s;">
                Become Seller
            </button>
        </form>
    <?php endif; ?>
</section>

<footer>
    <div class="footer-container">
        <div>
            <h3>Categories</h3>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li><a href="<?= e($baseUrl) ?>category.php?name=<?= urlencode($category) ?>"><?= e($category) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- Additional footer columns can go here -->
    </div>
    <div class="copyright">&copy; Fiverr International Ltd. 2025</div>
</footer>

</body>
</html>
