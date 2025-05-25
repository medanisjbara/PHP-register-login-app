<?php
// orders.php - Placeholder for Orders (user dashboard)
session_start();
if(!isset($_COOKIE['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="p-4 bg-white rounded shadow-sm">
        <h1>Your Orders</h1>
        <p>This page will allow you to check active and past orders.</p>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
    </div>
</div>
</body>
</html>