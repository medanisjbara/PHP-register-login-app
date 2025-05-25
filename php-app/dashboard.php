<?php
session_start();

// Check if the user is logged in
if(!isset($_COOKIE['user'])) {
    Redirect::go('login');
}

require_once 'classes/Cookies.php';
require_once 'classes/DB.php';
$cookie = new Cookie();
$userId = $cookie->getId();
$db = DB::getInstance();
$userArr = $db->getData("*", "users", ["id", "=", $userId]);
$user = $userArr ? $userArr[0] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Fiverr Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Fiverr Clone</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">Welcome, <?= htmlspecialchars($user ? $user['username'] : 'User') ?>!</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="p-4 bg-white rounded shadow-sm">
        <h1 class="mb-4">Dashboard</h1>
        <div class="mb-3">
            <?php if ($user && !empty($user['is_seller'])): ?>
                <span class="badge bg-success">You are a seller</span>
            <?php else: ?>
                <a href="become_seller.php" class="btn btn-success">Become a Seller</a>
            <?php endif; ?>
        </div>
        <p>This is your dashboard. You can manage your gigs, orders, and profile here.</p>

        <hr>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">My Gigs</h5>
                        <p class="card-text">View and manage your service listings.</p>
                        <a href="gigs.php" class="btn btn-light btn-sm">Go to Gigs</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>
                        <p class="card-text">Check active and past orders.</p>
                        <a href="orders.php" class="btn btn-light btn-sm">Go to Orders</a>
                    </div>
                </div>
            </div>
            <?php if ($user && !empty($user['is_seller'])): ?>
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Sales</h5>
                        <p class="card-text">Track your sales as a seller.</p>
                        <a href="sales.php" class="btn btn-light btn-sm">View Sales</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Profile</h5>
                        <p class="card-text">Update your profile and settings.</p>
                        <a href="profile.php" class="btn btn-light btn-sm">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
