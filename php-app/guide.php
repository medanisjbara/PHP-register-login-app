<?php
// guide.php - Placeholder for Individual Guide
session_start();
$title = isset($_GET['title']) ? htmlspecialchars($_GET['title']) : 'Guide';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="p-4 bg-white rounded shadow-sm">
        <h1><?= $title ?></h1>
        <p>This page will show details for the guide: <b><?= $title ?></b>.</p>
        <a href="guides.php" class="btn btn-secondary btn-sm">Back to Guides</a>
    </div>
</div>
</body>
</html>