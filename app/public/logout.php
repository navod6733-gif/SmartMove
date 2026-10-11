<?php
require_once __DIR__ . '/../includes/functions.php';
session_destroy();
session_start();
flash('You have been logged out.');
header('Location: index.php');
exit;
