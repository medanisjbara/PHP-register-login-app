<?php
require_once 'classes/User.php';
require_once 'classes/Input.php';
require_once 'classes/Redirect.php';
require_once 'classes/Cookies.php';

session_start();

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Redirect logged-in users away
if (isset($_COOKIE['user'])) {
    Redirect::go('dashboard');
}

$errors = [];

if (Input::exist()) {
    $users = new User();

    $user_input = [
        "username" => $_POST["username"] ?? '',
        "password" => $_POST["password"] ?? ''
    ];

    // Basic validation
    if (trim($user_input['username']) === '') {
        $errors[] = "Username is required.";
    }
    if (trim($user_input['password']) === '') {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $findUser = $users->findUser($user_input);

        if ($findUser) {
            Cookie::new('user', $findUser, 60*60*24);
            Redirect::go('dashboard');
        } else {
            $errors[] = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login - Your Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        /* Same styling from your other project for consistency */
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
        .login-form-section {
            max-width: 400px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .login-form label {
            display: block;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
            font-weight: bold;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-form button {
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
        .alternative-register {
            text-align: center;
            margin: 2rem auto;
            font-size: 0.9rem;
        }
        footer {
            font-size: 0.9rem;
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
    <a href="/" class="header-logo">YourProjectName</a>
    <nav class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="blog.php">Blog</a>
        <a href="register.php">Register</a>
    </nav>
    <div class="header-actions">
        <a href="register.php" class="join-btn">Join</a>
    </div>
</header>

<section class="hero">
    <h1>Welcome Back to YourProjectName</h1>
    <h2>Please sign in to continue.</h2>
</section>

<section class="login-form-section">
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="login-form" method="post" action="">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required autocomplete="off" value="<?= e($_POST['username'] ?? '') ?>">

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>
</section>

<section class="alternative-register">
    Don't have an account? <a href="register.php">Register here</a>.
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
        &copy; <?= date("Y") ?> YourProjectName. All rights reserved.
    </div>
</footer>

</body>
</html>
