<?php
/**
 * Database configuration.
 *
 * NOT ACTIVE while this project lives on GitHub Pages — GitHub Pages only
 * serves static files and cannot execute PHP at all. This file (and the
 * rest of /backend) is here so the project is ready to move onto a real
 * PHP + MySQL host later without a rewrite. Until then, the front end
 * reads /data/profile.json directly (see assets/js/app.js).
 *
 * To activate:
 *   1. Deploy this project to a PHP-capable host (cPanel, VPS, etc.)
 *   2. Create the database using backend/schema.sql
 *   3. Fill in the credentials below
 *   4. Uncomment the connection code
 *   5. In assets/js/app.js, switch DATA_ENDPOINT to "backend/api/get_profile.php"
 */

// --- MySQL credentials (fill in on a real host) ---
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'sc_profile');
// define('DB_USER', 'sc_profile_user');
// define('DB_PASS', 'change-me');

/**
 * Returns a PDO connection to the MySQL database.
 * Commented out — uncomment once the credentials above are set.
 */
// function get_db_connection(): PDO {
//     $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
//     $options = [
//         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//         PDO::ATTR_EMULATE_PREPARES   => false,
//     ];
//     return new PDO($dsn, DB_USER, DB_PASS, $options);
// }

// Path used instead of the database while running on static JSON.
define('STATIC_DATA_PATH', __DIR__ . '/../data/profile.json');
