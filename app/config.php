<?php
// Copy this file to config.php (same folder) and fill in your real values.
// config.php is what the app actually loads - keep it out of version control.

// ---- Oracle ----
define('ORACLE_USER', 'system');
define('ORACLE_PASS', '12345');
define('ORACLE_CONN_STR', 'localhost:1521/xe'); // host:port/service_name

// ---- MongoDB ----
define('MONGO_URI', 'mongodb://localhost:27017');
define('MONGO_DB', 'smartmove');

// ---- Admin login (simple demo auth - not for production use) ----
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // change this!
