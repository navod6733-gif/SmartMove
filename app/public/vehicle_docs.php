<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Vehicle Documents';
require_admin();

$vehicleId = (int)($_GET['vehicle_id'] ?? 0);
$error = null;
$vehicle = null;
$docs = [];

try {
    $rows = oracle_select("SELECT vehicle_id, vehicle_number, vehicle_type FROM vehicles WHERE vehicle_id = :id", [':id' => $vehicleId]);
    if (empty($rows)) {
        $error = 'Vehicle not found.';
    } else {
        $vehicle = $rows[0];
        $docs = mongo_find('vehicle_documents', ['vehicle_id' => $vehicleId], ['sort' => ['doc_type' => 1]]);
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    try {
        mongo_insert('vehicle_documents', [
            'vehicle_id'     => $vehicleId,
            'vehicle_number' => $vehicle['VEHICLE_NUMBER'],
            'doc_type'       => $_POST['doc_type'],
            'title'          => trim($_POST['title']),
            'url'            => trim($_POST['url']),
            'uploaded_at'    => new MongoDB\BSON\UTCDateTime(),
        ]);
        flash('Document added.');
        header('Location: vehicle_docs.php?vehicle_id=' . $vehicleId);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

require __DIR__ . '/header.php';
?>
<h1>Documents &mdash; <?= h($vehicle['VEHICLE_NUMBER'] ?? '') ?></h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php else: ?>
    <div class="card">
        <h2 style="margin-top:0">Add document / media</h2>
        <form method="post" class="grid-2">
            <div>
                <label>Type</label>
                <select name="doc_type">
                    <option value="PHOTO">Photo</option>
                    <option value="INSURANCE">Insurance</option>
                    <option value="REGISTRATION">Registration</option>
                    <option value="INSPECTION">Inspection</option>
                </select>
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            <div>
                <label>URL</label>
                <input type="url" name="url" required placeholder="https://...">
            </div>
            <div style="grid-column:1/-1"><button type="submit">Add</button></div>
        </form>
    </div>

    <table>
        <tr><th>Type</th><th>Title</th><th>URL</th><th>Uploaded</th></tr>
        <?php foreach ($docs as $d): ?>
            <tr>
                <td><?= h($d['doc_type']) ?></td>
                <td><?= h($d['title']) ?></td>
                <td><a href="<?= h($d['url']) ?>" target="_blank" rel="noopener">open</a></td>
                <td><?= isset($d['uploaded_at']['$date']) ? h($d['uploaded_at']['$date']) : '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<p><a href="vehicles.php">&larr; Back to vehicles</a></p>
<?php require __DIR__ . '/footer.php'; ?>
