<?php require_once __DIR__ . '/../includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle) ? h($pageTitle) . ' - ' : '' ?>SmartMove Transport Solutions</title>
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="brand"><a href="/index.php">SmartMove</a></div>
    <nav>
        <a href="/routes.php">Routes</a>
        <a href="/trips.php">Trips</a>
        <a href="/reviews.php">Reviews</a>
        <a href="/announcements.php">Announcements</a>
        <?php if (current_passenger()): ?>
            <a href="/my_tickets.php">My Tickets</a>
        <?php endif; ?>
        <?php if (is_admin()): ?>
            <span class="nav-sep">|</span>
            <a href="/vehicles.php">Vehicles</a>
            <a href="/drivers.php">Drivers</a>
            <a href="/admin_trips.php">Trips Admin</a>
            <a href="/maintenance.php">Maintenance</a>
            <a href="/reports/most_frequent_routes.php">Reports</a>
        <?php endif; ?>
        <span class="nav-sep">|</span>
        <?php if (current_passenger()): ?>
            <span class="who">Hi, <?= h(current_passenger()['full_name']) ?></span>
            <a href="/logout.php">Logout</a>
        <?php elseif (is_admin()): ?>
            <span class="who">Admin</span>
            <a href="/logout.php">Logout</a>
        <?php else: ?>
            <a href="/login.php">Login</a>
            <a href="/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
<?php foreach (get_flashes() as $f): ?>
    <div class="flash flash-<?= h($f['type']) ?>"><?= h($f['msg']) ?></div>
<?php endforeach; ?>
