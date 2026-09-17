<?php
/**
 * GET /backend/api/get_profile.php
 *
 * Returns the profile as JSON, in the exact shape the front end expects
 * (see /data/profile.json for the reference shape).
 *
 * Currently: reads the static JSON file, so behavior is identical to what
 * GitHub Pages already serves. This lets you swap DATA_ENDPOINT in
 * assets/js/app.js to this file at any time with zero front-end changes.
 *
 * Once on a real MySQL host: uncomment the DB block below and delete (or
 * ignore) the static-file block.
 */

require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');

// ---------------------------------------------------------------------
// ACTIVE (static JSON) — used while there is no live database
// ---------------------------------------------------------------------
$json = file_get_contents(STATIC_DATA_PATH);
if ($json === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not read profile data']);
    exit;
}
echo $json;
exit;

// ---------------------------------------------------------------------
// COMMENTED (MySQL) — uncomment once backend/config.php has real
// credentials and get_db_connection() is uncommented there too
// ---------------------------------------------------------------------
/*
try {
    $pdo = get_db_connection();

    $person = $pdo->query('SELECT name, title, company, bio, photo, location FROM person LIMIT 1')->fetch();
    $contact = $pdo->query('SELECT phone, whatsapp, email FROM contact LIMIT 1')->fetch();
    $links = $pdo->query('SELECT label, url FROM links ORDER BY sort_order ASC')->fetchAll();
    $portfolio = $pdo->query('SELECT title, description, image, link FROM portfolio ORDER BY sort_order ASC')->fetchAll();

    echo json_encode([
        'person'    => $person,
        'contact'   => $contact,
        'links'     => $links,
        'portfolio' => $portfolio,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'detail' => $e->getMessage()]);
}
*/
