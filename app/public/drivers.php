<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Drivers';
require_admin();

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        oracle_execute(
            "INSERT INTO drivers (full_name, license_number, phone, email, status)
             VALUES (:name, :lic, :phone, :email, 'ACTIVE')",
            [
                ':name' => trim($_POST['full_name']),
                ':lic' => trim($_POST['license_number']),
                ':phone' => trim($_POST['phone']),
                ':email' => trim($_POST['email']),
            ]
        );
        flash('Driver added.');
        header('Location: drivers.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$drivers = [];
try {
    $drivers = oracle_select(
        "SELECT d.driver_id, d.full_name, d.license_number, d.phone, d.email, d.status,
                v.vehicle_number
         FROM drivers d
         LEFT JOIN driver_vehicle_assignments a ON a.driver_id = d.driver_id AND a.status = 'CURRENT'
         LEFT JOIN vehicles v ON v.vehicle_id = a.vehicle_id
         ORDER BY d.full_name"
    );
} catch (Exception $e) {
    $error = $error ?: $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>Drivers</h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php endif; ?>

<div class="card">
    <h2 style="margin-top:0">Add Driver</h2>
    <form method="post" class="grid-2">
        <div>
            <label>Full name</label>
            <input type="text" name="full_name" required>
            <label>License number</label>
            <input type="text" name="license_number" required>
        </div>
        <div>
            <label>Phone</label>
            <input type="text" name="phone" required>
            <label>Email</label>
            <input type="email" name="email">
        </div>
        <div style="grid-column:1/-1"><button type="submit">Add driver</button></div>
    </form>
</div>

<table>
    <tr><th>Name</th><th>License</th><th>Phone</th><th>Status</th><th>Current vehicle</th></tr>
    <?php foreach ($drivers as $d): ?>
        <tr>
            <td><?= h($d['FULL_NAME']) ?></td>
            <td><?= h($d['LICENSE_NUMBER']) ?></td>
            <td><?= h($d['PHONE']) ?></td>
            <td>
                <?php $b = $d['STATUS'] === 'ACTIVE' ? 'badge-ok' : 'badge-warn'; ?>
                <span class="badge <?= $b ?>"><?= h($d['STATUS']) ?></span>
            </td>
            <td><?= h($d['VEHICLE_NUMBER'] ?? 'Unassigned') ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require __DIR__ . '/footer.php'; ?>
