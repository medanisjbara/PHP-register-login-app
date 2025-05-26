<?php
session_start();
require_once 'classes/Cookies.php';
require_once 'classes/DB.php';
require_once 'classes/Redirect.php'; // make sure this is included

// Check if user is logged in
if (!isset($_COOKIE['user'])) {
    Redirect::go("login.php");
    exit;
}

$cookie = new Cookie();
$userId = $cookie->getId();
$db = DB::getInstance();
$userArr = $db->getData("*", "users", ["id", "=", $userId]);
$user = $userArr && is_array($userArr) ? $userArr[0] : null;

$is_seller = $user && !empty($user['is_seller']);
$errors = [];
$success = "";

// Handle gig creation using addData
if ($is_seller && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description'], $_POST['price'], $_POST['category'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    if ($title === "" || $description === "" || $category === "" || $price <= 0) {
        $errors[] = "All fields are required and price must be positive.";
    } else {
        $result = $db->addData("gigs", [
            "user_id" => $userId,
            "title" => $title,
            "description" => $description,
            "price" => $price,
            "category" => $category
        ]);
        if ($result === true) {
            Redirect::go("gigs.php");
            exit;
        } else {
            $errors[] = "Database error: Unable to create gig.";
        }
    }
}

// Handle application to gig using addData
if (isset($_POST['apply_gig_id'])) {
    $gig_id = intval($_POST['apply_gig_id']);
    // Check if already applied or is the owner
    $gig = $db->getData("*", "gigs", ["id", "=", $gig_id]);
    if (!$gig || !is_array($gig)) {
        $errors[] = "Gig not found.";
    } elseif ($gig[0]['user_id'] == $userId) {
        $errors[] = "You cannot apply to your own gig.";
    } else {
        $app = $db->getData("*", "gig_applications", ["gig_id", "=", $gig_id, "AND", "user_id", "=", $userId]);
        if ($app && is_array($app) && count($app) > 0) {
            $errors[] = "You have already applied to this gig.";
        } else {
            $result = $db->addData("gig_applications", [
                "gig_id" => $gig_id,
                "user_id" => $userId
            ]);
            if ($result === true) {
                Redirect::go("gigs.php");
                exit;
            } else {
                $errors[] = "Database error: Unable to apply to gig.";
            }
        }
    }
}

// Fetch all gigs (not just own)
$gigs = [];
$tmp = $db->getData("*", "gigs");
if (is_array($tmp)) {
    $gigs = $tmp;
}

// Fetch this user's applications
$app_gigs = [];
$applications = $db->getData("*", "gig_applications", ["user_id", "=", $userId]);
if (is_array($applications)) {
    foreach($applications as $app) {
        if (isset($app['gig_id'])) {
            $app_gigs[$app['gig_id']] = true;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gigs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .gig-card { min-height: 210px; }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="p-4 bg-white rounded shadow-sm">
        <h1 class="mb-4">Gigs</h1>
        <?php if ($is_seller): ?>
        <div class="mb-4">
            <h4>Create a New Gig</h4>
            <?php if ($errors): ?>
                <div class="alert alert-danger"><?php echo implode('<br>', $errors); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form method="POST" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="title" class="form-control" placeholder="Gig Title" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="category" class="form-control" placeholder="Category" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-success w-100">Create</button>
                </div>
            </form>
        </div>
        <?php else: ?>
            <?php if ($errors): ?>
                <div class="alert alert-danger"><?php echo implode('<br>', $errors); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <h4 class="mb-3">All Gigs</h4>
        <?php if (is_array($gigs) && count($gigs) > 0): ?>
            <div class="row">
                <?php foreach ($gigs as $gig): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card gig-card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($gig['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($gig['description']) ?></p>
                                <p class="card-text"><b>Category:</b> <?= htmlspecialchars($gig['category']) ?></p>
                                <p class="card-text"><b>Price:</b> $<?= number_format($gig['price'], 2) ?></p>
                                <?php if ($gig['user_id'] == $userId): ?>
                                    <span class="badge bg-primary">Your gig</span>
                                <?php else: ?>
                                    <?php if (isset($app_gigs[$gig['id']])): ?>
                                        <span class="badge bg-success">Applied</span>
                                    <?php else: ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="apply_gig_id" value="<?= $gig['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-success">Apply</button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div>No gigs available.</div>
        <?php endif; ?>
        <a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
