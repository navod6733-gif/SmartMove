<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Trips';

$routeId = isset($_GET['route_id']) ? (int)$_GET['route_id'] : null;
$dateFilter = trim($_GET['date'] ?? '');

$routes = [];
$trips = [];
$error = null;
try {
    $routes = oracle_select("SELECT route_id, route_name FROM routes WHERE status='ACTIVE' ORDER BY route_name");

    $sql = "SELECT t.trip_id, t.trip_date, t.departure_time, t.arrival_time, t.fare, t.status,
                   r.route_name, r.origin, r.destination,
                   v.vehicle_number, v.vehicle_type, v.capacity,
                   d.full_name AS driver_name
            FROM trips t
            JOIN routes r ON r.route_id = t.route_id
            JOIN vehicles v ON v.vehicle_id = t.vehicle_id
            JOIN drivers d ON d.driver_id = t.driver_id
            WHERE t.status = 'SCHEDULED'";
    $binds = [];
    if ($routeId) {
        $sql .= " AND t.route_id = :rid";
        $binds[':rid'] = $routeId;
    }
    if ($dateFilter !== '') {
        $sql .= " AND t.trip_date = TO_DATE(:d, 'YYYY-MM-DD')";
        $binds[':d'] = $dateFilter;
    }
    $sql .= " ORDER BY t.trip_date, t.departure_time";

    $trips = oracle_select($sql, $binds);
    foreach ($trips as &$t) {
        $t['available'] = fn_available_seats_call($t['TRIP_ID']);
    }
    unset($t);
} catch (Exception $e) {
    $error = $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>Upcoming Trips</h1>

<div class="card">
    <form method="get">
        <div class="grid-2">
            <div>
                <label>Route</label>
                <select name="route_id">
                    <option value="">All routes</option>
                    <?php foreach ($routes as $r): ?>
                        <option value="<?= (int)$r['ROUTE_ID'] ?>" <?= $routeId == $r['ROUTE_ID'] ? 'selected' : '' ?>>
                            <?= h($r['ROUTE_NAME']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Date</label>
                <input type="date" name="date" value="<?= h($dateFilter) ?>">
            </div>
        </div>
        <button type="submit">Search</button>
    </form>
</div>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php elseif (empty($trips)): ?>
    <p class="muted">No scheduled trips match your search.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Route</th><th>Date</th><th>Departs</th><th>Vehicle</th><th>Driver</th><th>Fare</th><th>Seats left</th><th></th>
        </tr>
        <?php foreach ($trips as $t): ?>
            <tr>
                <td><?= h($t['ROUTE_NAME']) ?><br><span class="muted"><?= h($t['ORIGIN']) ?> &rarr; <?= h($t['DESTINATION']) ?></span></td>
                <td><?= fmt_date($t['TRIP_DATE']) ?></td>
                <td><?= fmt_datetime($t['DEPARTURE_TIME']) ?></td>
                <td><?= h($t['VEHICLE_TYPE']) ?> (<?= h($t['VEHICLE_NUMBER']) ?>)</td>
                <td><?= h($t['DRIVER_NAME']) ?></td>
                <td><?= fmt_money($t['FARE']) ?></td>
                <td>
                    <?php if ($t['available'] > 0): ?>
                        <span class="badge badge-ok"><?= (int)$t['available'] ?> free</span>
                    <?php else: ?>
                        <span class="badge badge-bad">Full</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($t['available'] > 0): ?>
                        <a class="btn" href="book.php?trip_id=<?= (int)$t['TRIP_ID'] ?>">Book</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
