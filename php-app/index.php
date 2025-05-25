<?php
require_once 'classes/User.php';
require_once 'classes/Input.php';
require_once 'classes/Redirect.php';
require_once 'classes/Cookies.php';

session_start();

// Redirect logged-in users to dashboard
 if(isset($_COOKIE['user'])) {
    Redirect::go('dashboard');
}

// Helper function to escape output safely
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Fetch data dynamically or fallback to static arrays
    // fallback static data
    $categories = [
        "Programming & Tech", "Graphics & Design", "Digital Marketing",
        "Writing & Translation", "Video & Animation", "AI Services",
        "Music & Audio", "Business", "Consulting"
    ];

    $services = [
        "Website Development", "Video Editing", "Software Development", "SEO",
        "Architecture & Interior Design", "Book Design", "UGC Videos",
        "Voice Over", "Social Media Marketing", "AI Development",
        "Logo Design", "Website Design"
    ];

    $guides = [
        "Start a side business",
        "Ecommerce business Ideas",
        "Start an online business and work from home",
        "Build a website from scratch",
        "Grow your business with AI",
        "Create a logo for your business"
    ];

// Logos could also be stored in DB or config
$logos = [
    ['src' => 'logos/company1.svg', 'alt' => 'Company 1'],
    ['src' => 'logos/company2.svg', 'alt' => 'Company 2'],
    ['src' => 'logos/company3.svg', 'alt' => 'Company 3'],
    ['src' => 'logos/company4.svg', 'alt' => 'Company 4'],
    ['src' => 'logos/company5.svg', 'alt' => 'Company 5'],
];

// Site base URL helper for asset paths (adjust as needed)
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Fiverr Clone - Home</title>
<link rel="stylesheet" href="<?= e($baseUrl) ?>styles/index.css" />
</head>
<body>

<header>
    <nav>
        <div class="nav-left">
            <a href="<?= e($baseUrl) ?>become_seller.php">Become a Seller</a>
            <a href="<?= e($baseUrl) ?>fiverr_go.php">Fiverr Go</a>
        </div>
        <div class="nav-categories" aria-label="Main categories">
            <?php foreach ($categories as $category): ?>
                <a href="<?= e($baseUrl) ?>category.php?name=<?= urlencode($category) ?>"><?= e($category) ?></a>
            <?php endforeach; ?>
        </div>
        <div class="nav-right">
            <a href="<?= e($baseUrl) ?>login.php">Sign in</a>
            <button onclick="location.href='<?= e($baseUrl) ?>register.php'">Join</button>
        </div>
    </nav>
</header>

<section class="hero">
    <h1>Our freelancers will take it from here</h1>
    <div class="trusted-logos" aria-label="Trusted by companies">
        <?php foreach ($logos as $logo): ?>
            <img src="<?= e($baseUrl . $logo['src']) ?>" alt="<?= e($logo['alt']) ?>" loading="lazy" />
        <?php endforeach; ?>
    </div>
</section>

<section class="categories-showcase container" aria-label="Popular categories and services">
    <h2>Popular Categories</h2>
    <div class="categories-grid" role="list">
        <?php foreach ($categories as $category): ?>
            <a class="category-card" role="listitem" tabindex="0" href="<?= e($baseUrl) ?>category.php?name=<?= urlencode($category) ?>"><?= e($category) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="popular-services">
        <h3>Popular Services</h3>
        <ul class="popular-services-list">
            <?php foreach ($services as $service): ?>
                <li tabindex="0"><?= e($service) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="ai-section" aria-label="AI integration and instant results">
    <h2>Instant results. Top talent.</h2>
    <p>Get what you need faster from freelancers who trained their own personal AI Creation Models. Now you can browse, prompt, and generate instantly. And if you need a tweak or change, the freelancer is always there to help you perfect it.</p>
</section>

<section class="business-section" aria-label="Fiverr Business features">
    <h2>The premium freelance solution for businesses</h2>
    <div class="business-benefits">
        <div class="benefit">
            <strong>Dedicated hiring experts</strong>
            Count on an account manager to find you the right talent and see to your project’s every need.
        </div>
        <div class="benefit">
            <strong>Satisfaction guarantee</strong>
            Order confidently, with guaranteed refunds for less-than-satisfactory deliveries.
        </div>
        <div class="benefit">
            <strong>Advanced management tools</strong>
            Seamlessly integrate freelancers into your team and projects.
        </div>
        <div class="benefit">
            <strong>Flexible payment models</strong>
            Pay per project or opt for hourly rates to facilitate longer-term collaboration.
        </div>
    </div>
</section>

<section class="success-section" aria-label="Success stories on Fiverr">
    <h2>What success on Fiverr looks like</h2>
    <div class="success-story">
        <p><em>Vontélle Eyewear turns to Fiverr freelancers to bring their vision to life.</em></p>
        <ul class="trusted-services-list" aria-label="Vontélle's trusted services">
            <li>3D Industrial Design</li>
            <li>E-commerce Website Development</li>
            <li>Email Marketing</li>
            <li>Press Releases</li>
            <li>Logo Design</li>
        </ul>
    </div>
</section>

<section class="cta-section" aria-label="Call to action">
    <h2>Make it all happen with freelancers</h2>
    <ul class="cta-list">
        <li>Access a pool of top talent across 700 categories</li>
        <li>Enjoy a simple, easy-to-use matching experience</li>
        <li>Get quality work done quickly and within budget</li>
        <li>Only pay when you’re happy</li>
    </ul>
    <div class="made-on-fiverr">Made on Fiverr</div>
</section>

<section class="guides-section" aria-label="Guides and resources">
    <h2>Guides to help you grow</h2>
    <div class="guides-list">
        <?php foreach ($guides as $guide): ?>
            <a class="guide" tabindex="0" href="<?= e($baseUrl) ?>guide.php?title=<?= urlencode($guide) ?>"><?= e($guide) ?></a>
        <?php endforeach; ?>
    </div>
    <a class="guides-cta" href="<?= e($baseUrl) ?>guides.php">See more</a>
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
        <!-- Additional footer columns omitted for brevity -->
    </div>
    <div class="copyright">&copy; Fiverr International Ltd. 2025</div>
</footer>

</body>
</html>
