<?php
require_once 'classes/DB.php';  // Your DB connection class
require_once 'classes/Input.php';
require_once 'classes/Redirect.php';

session_start();

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Redirect if user is already logged in
if (isset($_COOKIE['user'])) {
    Redirect::go('blog');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_again = $_POST['password_again'] ?? '';

    // Basic validation
    if ($username === '') {
        $errors[] = "Username is required.";
    }
    if ($name === '') {
        $errors[] = "Name is required.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $password_again) {
        $errors[] = "Passwords do not match.";
    }

    // Check if username already exists
    if (empty($errors)) {
        $db = DB::getInstance();
        $result = $db->getData("*", "users", ["username", "=", "'$username'"]);
        if (is_array($result) && count($result) > 0) {
            $errors[] = "Username is already taken.";
        }
    }

    // Insert new user
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $db = DB::getInstance();
        $insert = $db->addData("users", [
            "username" => $username,
            "password" => $hash,
            "name" => $name
        ]);
        if ($insert !== true) {
            $errors[] = "Registration failed. Please try again.";
        } else {
            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register - Your Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            margin: 0;
            background: #f8f8f8;
        }
        header, footer {
            background-color: #222;
            color: white;
            padding: 1rem;
        }
        nav {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        nav a, nav button {
            color: white;
            margin-right: 1rem;
            text-decoration: none;
            font-size: 0.95rem;
        }
        nav button {
            background: #1dbf73;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }
        .hero {
            background: #1dbf73;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .registration-form-section {
            max-width: 500px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .registration-form label {
            display: block;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
            font-weight: bold;
        }
        .registration-form input {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .registration-form button {
            margin-top: 1.5rem;
            width: 100%;
            padding: 0.75rem;
            background: #1dbf73;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
        .error-messages ul {
            background: #ffdddd;
            padding: 1rem;
            border: 1px solid #ff5c5c;
            border-radius: 4px;
            list-style-type: none;
        }
        .success-message {
            background: #ddffdd;
            padding: 1rem;
            border: 1px solid #5cd65c;
            border-radius: 4px;
            text-align: center;
        }
        .alternative-signin {
            text-align: center;
            margin: 2rem auto;
            font-size: 0.9rem;
        }
        .separator {
            text-align: center;
            margin: 2rem auto;
            font-weight: bold;
        }
        .terms-info {
            text-align: center;
            font-size: 0.85rem;
            color: #777;
        }
        footer {
            font-size: 0.9rem;
        }
        footer h3 {
            margin-top: 0;
        }
        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .footer-container ul {
            list-style: none;
            padding-left: 0;
        }
        .footer-container li a {
            color: #ccc;
            text-decoration: none;
        }
        .footer-container li a:hover {
            text-decoration: underline;
        }
        .copyright {
            text-align: center;
            padding: 1rem 0 0.5rem;
            border-top: 1px solid #444;
            margin-top: 1rem;
        }
        /* Header styles */
        header {
            background-color: #222;
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1dbf73;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }
        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }
        .nav-links a:hover {
            color: #1dbf73;
        }
        .header-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }
        .header-actions a {
            color: #fff;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .join-btn {
            background-color: #1dbf73;
            color: #fff;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
        }
        .join-btn:hover {
            background-color: #17a865;
        }
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
            .nav-links, .header-actions {
                margin-top: 0.5rem;
                flex-direction: column;
                width: 100%;
            }
            .nav-links a, .header-actions a {
                padding: 0.5rem 0;
            }
            .header-actions {
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<header>
    <a href="/" class="header-logo">FiverClone</a>
    <nav class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="blog.php">Blog</a>
        <a href="login.php">Login</a>
    </nav>
    <div class="header-actions">
        <a href="login.php">Sign in</a>
        <a href="register.php" class="join-btn">Join</a>
    </div>
</header>

<section class="hero">
    <h1>Welcome to FiverClone</h1>
    <h2>Register to get started with our awesome platform.</h2>
</section>

<section class="registration-form-section">
    <?php if ($success): ?>
        <div class="success-message">
            Registration successful! <a href="login.php">Sign in here</a>.
        </div>
    <?php else: ?>
        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?> <li><?= e($error) ?></li> <?php endforeach; ?> </ul> </div> <?php endif; ?>

    <form class="registration-form" method="POST" action="">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required value="<?= e($_POST['username'] ?? '') ?>">

        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" required value="<?= e($_POST['name'] ?? '') ?>">

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <label for="password_again">Confirm Password</label>
        <input type="password" name="password_again" id="password_again" required>

        <button type="submit">Register</button>
    </form>
<?php endif; ?>

</section>

<section class="alternative-signin">
    Already have an account? <a href="login.php">Sign in here</a>.
</section>

<section class="terms-info">
    By joining, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
</section>

<footer>
    <div class="footer-container">
        <div>
            <h3>Explore</h3>
            <ul>
                <li><a href="#">Features</a></li>
                <li><a href="#">Pricing</a></li>
                <li><a href="#">Blog</a></li>
            </ul>
        </div>
        <div>
            <h3>About</h3>
            <ul>
                <li><a href="#">Company</a></li>
                <li><a href="#">Jobs</a></li>
                <li><a href="#">Press</a></li>
            </ul>
        </div>
        <div>
            <h3>Help</h3>
            <ul>
                <li><a href="#">Support</a></li>
                <li><a href="#">Docs</a></li>
                <li><a href="#">Status</a></li>
            </ul>
        </div>
    </div>
    <div class="copyright">
        &copy; <?= date("Y") ?> FiverClone. All rights reserved.
    </div>
</footer>

</body>
</html>
