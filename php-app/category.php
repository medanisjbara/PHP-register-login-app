<?php
// category.php - Placeholder for Category Listings
session_start();
$category = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Category';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $category ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="p-4 bg-white rounded shadow-sm">
        <h1><?= $category ?></h1>
        <p>This page will show all gigs/services for the category: <b><?= $category ?></b>.</p>
        <a href="index.php" class="btn btn-secondary btn-sm">Back to Home</a>
    </div>
</div>
</body>
</html>