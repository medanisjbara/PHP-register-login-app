<?php
// sales.php - Placeholder for Sales (seller dashboard)
session_start();
if(!isset($_COOKIE['user'])) {
    header("Location: login.php");
    exit;
}
require_once 'classes/Cookies.php';
require_once 'classes/DB.php';
$cookie = new Cookie();
$userId = $cookie->getId();
$db = DB::getInstance();
$userArr = $db->getData("*", "users", ["id", "=", $userId]);
$user = $userArr ? $userArr[0] : null;
if(!$user || empty($user['is_seller'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="p-4 bg-white rounded shadow-sm">
        <h1>Sales</h1>
        <p>This page will allow you to track your sales as a seller.</p>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
    </div>
</div>
</body>
</html>