<?php
require_once __DIR__ . '/../../includes/functions.php';
$pageTitle = 'Top Rated Report';
require_admin();

$drivers = $vehicles = $mongoDrivers = $mongoVehicles = [];
$error = null;
try {
    $drivers = call_report('rpt_top_rated_drivers');
    $vehicles = call_report('rpt_top_rated_vehicles');
} catch (Exception $e) {
    $error = $e->getMessage();
}

try {
    $mongoDrivers = mongo_aggregate('reviews', [
        ['$group' => ['_id' => '$driver_id', 'avg_rating' => ['$avg' => '$rating'], 'reviews_count' => ['$sum' => 1]]],
        ['$sort' => ['avg_rating' => -1]],
    ]);
    $mongoVehicles = mongo_aggregate('reviews', [
        ['$group' => ['_id' => '$vehicle_id', 'avg_rating' => ['$avg' => '$rating'], 'reviews_count' => ['$sum' => 1]]],
        ['$sort' => ['avg_rating' => -1]],
    ]);
} catch (Exception $e) {
    $mongoError = $e->getMessage();
}

require __DIR__ . '/../header.php';
?>
<h1>Report: Top Rated Drivers &amp; Vehicles</h1>
<p class="muted">Structured ratings from Oracle (<code>rpt_top_rated_drivers</code> / <code>rpt_top_rated_vehicles</code>),
cross-checked against the raw review documents in MongoDB.</p>

<div class="card" style="padding:0.5rem 1rem">
    <a href="most_frequent_routes.php">Most frequent routes</a> &middot;
    <a href="revenue.php">Revenue by period</a> &middot;
    <a href="passenger_history.php">Passenger history</a> &middot;
    <a href="maintenance_due.php">Maintenance due</a> &middot;
    <a href="top_rated.php">Top rated</a>
</div>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php else: ?>
<div class="grid-2">
    <div>
        <h2>Top Drivers (Oracle)</h2>
        <table>
            <tr><th>Driver</th><th>Avg rating</th><th>Reviews</th><th>Trips driven</th></tr>
            <?php foreach ($drivers as $d): ?>
                <tr>
                    <td><?= h($d['FULL_NAME']) ?></td>
                    <td><span class="stars"><?= str_repeat('★', (int)round($d['AVG_RATING'])) ?></span> <?= h($d['AVG_RATING']) ?></td>
                    <td><?= (int)$d['REVIEWS_COUNT'] ?></td>
                    <td><?= (int)$d['TRIPS_DRIVEN'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <h2>Top Vehicles (Oracle)</h2>
        <table>
            <tr><th>Vehicle</th><th>Avg rating</th><th>Reviews</th></tr>
            <?php foreach ($vehicles as $v): ?>
                <tr>
                    <td><?= h($v['VEHICLE_NUMBER']) ?></td>
                    <td><span class="stars"><?= str_repeat('★', (int)round($v['AVG_RATING'])) ?></span> <?= h($v['AVG_RATING']) ?></td>
                    <td><?= (int)$v['REVIEWS_COUNT'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php if (!empty($mongoError)): ?>
    <div class="flash flash-error">MongoDB cross-check unavailable: <?= h($mongoError) ?></div>
<?php else: ?>
<h2>MongoDB Cross-check (raw review documents)</h2>
<div class="grid-2">
    <div>
        <p class="muted">Driver ID &middot; avg rating &middot; # reviews</p>
        <table>
            <tr><th>Driver ID</th><th>Avg rating</th><th>Reviews</th></tr>
            <?php foreach ($mongoDrivers as $d): ?>
                <tr><td><?= h($d['_id']) ?></td><td><?= round($d['avg_rating'], 2) ?></td><td><?= (int)$d['reviews_count'] ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <p class="muted">Vehicle ID &middot; avg rating &middot; # reviews</p>
        <table>
            <tr><th>Vehicle ID</th><th>Avg rating</th><th>Reviews</th></tr>
            <?php foreach ($mongoVehicles as $v): ?>
                <tr><td><?= h($v['_id']) ?></td><td><?= round($v['avg_rating'], 2) ?></td><td><?= (int)$v['reviews_count'] ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../footer.php'; ?>
