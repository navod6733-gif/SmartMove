<?php
// ============================================================
// Shared helpers: session, auth, flash messages, small utilities
// ============================================================
require_once __DIR__ . '/db_oracle.php';
require_once __DIR__ . '/db_mongo.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function h($str) {
    return htmlspecialchars((string)($str ?? ''), ENT_QUOTES, 'UTF-8');
}

function flash($msg, $type = 'success') {
    $_SESSION['flash'][] = ['msg' => $msg, 'type' => $type];
}

function get_flashes() {
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

function current_passenger() {
    return $_SESSION['passenger'] ?? null;
}

function require_login() {
    if (!current_passenger()) {
        flash('Please log in to continue.', 'error');
        header('Location: login.php');
        exit;
    }
}

function is_admin() {
    return !empty($_SESSION['is_admin']);
}

function require_admin() {
    if (!is_admin()) {
        flash('Admin login required for that page.', 'error');
        header('Location: login.php');
        exit;
    }
}

function fmt_money($n) {
    return 'LKR ' . number_format((float)$n, 2);
}

function fmt_date($d) {
    if (!$d) return '-';
    $t = strtotime($d);
    return $t ? date('d M Y', $t) : h($d);
}

function fmt_datetime($d) {
    if (!$d) return '-';
    $t = strtotime($d);
    return $t ? date('d M Y, h:i A', $t) : h($d);
}
