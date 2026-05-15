<?php require_once __DIR__ . '/../config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vice City Realty</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/real_estate_portal/assets/style.css">
</head>
<body>
<header>
    <div class="header-banner">
        <h1>Vice City Realty</h1>
        <div class="header-tagline">Premium Properties &mdash; South Florida &mdash; Est. 2026</div>
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="properties.php">Listings</a>
        <?php if (isset($_SESSION['user'])): ?>
            <?php if (in_array($_SESSION['user']['userType'], ['buyer', 'renter'])): ?>
                <a href="favorites.php">Favorites</a>
            <?php endif; ?>
            <?php if ($_SESSION['user']['userType'] === 'agent'): ?>
                <a href="add_property.php">Add Listing</a>
            <?php endif; ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main>
