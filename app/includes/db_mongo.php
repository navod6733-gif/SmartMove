<?php
// ============================================================
// MongoDB connection + helper functions
// Uses the low-level MongoDB\Driver\* classes that ship with the
// "mongodb" PHP extension directly - no Composer library required.
// ============================================================
require_once __DIR__ . '/../config.php';

function mongo_manager() {
    static $manager = null;
    if ($manager === null) {
        try {
            $manager = new MongoDB\Driver\Manager(MONGO_URI);
        } catch (Exception $e) {
            die('MongoDB connection failed: ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $manager;
}

// Converts a BSON document/cursor result into a plain PHP associative array
function bson_to_array($doc) {
    return json_decode(json_encode($doc), true);
}

function mongo_find($collection, array $filter = [], array $options = []) {
    $manager = mongo_manager();
    $query = new MongoDB\Driver\Query($filter, $options);
    try {
        $cursor = $manager->executeQuery(MONGO_DB . '.' . $collection, $query);
    } catch (Exception $e) {
        throw new Exception('Mongo find failed: ' . $e->getMessage());
    }
    $rows = [];
    foreach ($cursor as $doc) {
        $rows[] = bson_to_array($doc);
    }
    return $rows;
}

function mongo_aggregate($collection, array $pipeline) {
    $manager = mongo_manager();
    $cmd = new MongoDB\Driver\Command([
        'aggregate' => $collection,
        'pipeline'  => $pipeline,
        'cursor'    => new stdClass(),
    ]);
    try {
        $cursor = $manager->executeCommand(MONGO_DB, $cmd);
    } catch (Exception $e) {
        throw new Exception('Mongo aggregate failed: ' . $e->getMessage());
    }
    $rows = [];
    foreach ($cursor as $doc) {
        $rows[] = bson_to_array($doc);
    }
    return $rows;
}

function mongo_insert($collection, array $doc) {
    $manager = mongo_manager();
    $bulk = new MongoDB\Driver\BulkWrite();
    if (!isset($doc['created_at'])) {
        $doc['created_at'] = new MongoDB\BSON\UTCDateTime();
    }
    $id = $bulk->insert($doc);
    try {
        $manager->executeBulkWrite(MONGO_DB . '.' . $collection, $bulk);
    } catch (Exception $e) {
        throw new Exception('Mongo insert failed: ' . $e->getMessage());
    }
    return (string) $id;
}

// Simple keyword search helper using the text index created in 05_seed_mongo.js
function mongo_text_search($collection, $keyword) {
    return mongo_find($collection, ['$text' => ['$search' => $keyword]]);
}
