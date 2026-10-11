<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Reviews';

$routeId = isset($_GET['route_id']) && $_GET['route_id'] !== '' ? (int)$_GET['route_id'] : null;
$keyword = trim($_GET['q'] ?? '');

$routes = [];
$reviews = [];
$error = null;
try {
    $routes = oracle_select("SELECT route_id, route_name FROM routes ORDER BY route_name");

    if ($keyword !== '') {
        $reviews = mongo_text_search('reviews', $keyword);
    } elseif ($routeId) {
        $reviews = mongo_find('reviews', ['route_id' => $routeId], ['sort' => ['created_at' => -1]]);
    } else {
        $reviews = mongo_find('reviews', [], ['sort' => ['created_at' => -1], 'limit' => 20]);
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>Passenger Reviews</h1>

<div class="card">
    <form method="get" class="grid-2">
        <div>
            <label>Filter by route</label>
            <select name="route_id" onchange="this.form.submit()">
                <option value="">All routes</option>
                <?php foreach ($routes as $r): ?>
                    <option value="<?= (int)$r['ROUTE_ID'] ?>" <?= $routeId == $r['ROUTE_ID'] ? 'selected' : '' ?>>
                        <?= h($r['ROUTE_NAME']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Or search by keyword</label>
            <input type="text" name="q" value="<?= h($keyword) ?>" placeholder="e.g. late, brakes, comfortable">
            <button type="submit">Search</button>
        </div>
    </form>
</div>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php elseif (empty($reviews)): ?>
    <p class="muted">No reviews found.</p>
<?php else: ?>
    <?php foreach ($reviews as $r): ?>
        <div class="card">
            <span class="stars"><?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']) ?></span>
            <?php if (!empty($r['reported'])): ?><span class="badge badge-bad">Flagged</span><?php endif; ?>
            <p><?= h($r['comment'] ?? '') ?></p>
            <p class="muted">
                <?= h($r['route_name'] ?? '') ?>
                <?php if (!empty($r['tags'])): ?>
                    &middot; <?= implode(', ', array_map('h', $r['tags'])) ?>
                <?php endif; ?>
            </p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
