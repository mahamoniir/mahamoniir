<?php
/**
 * POST /backend/api/update_profile.php
 *
 * Accepts the same JSON shape as get_profile.php returns, and saves it.
 *
 * Currently: overwrites /data/profile.json directly. This only works on a
 * host that actually runs PHP (NOT GitHub Pages — GitHub Pages is
 * static-only and ignores .php files entirely). Until this project is on
 * a PHP host, edit /data/profile.json by hand and commit the change.
 *
 * Once on a real MySQL host: uncomment the DB block below and delete (or
 * ignore) the static-file block. Consider adding authentication before
 * exposing this endpoint publicly — it currently has none.
 */

require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON body']);
    exit;
}

// ---------------------------------------------------------------------
// ACTIVE (static JSON) — used while there is no live database
// ---------------------------------------------------------------------
$ok = file_put_contents(
    STATIC_DATA_PATH,
    json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
);

if ($ok === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not write profile data']);
    exit;
}
echo json_encode(['status' => 'saved']);
exit;

// ---------------------------------------------------------------------
// COMMENTED (MySQL) — uncomment once backend/config.php has real
// credentials and get_db_connection() is uncommented there too
// ---------------------------------------------------------------------
/*
try {
    $pdo = get_db_connection();
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'UPDATE person SET name=?, title=?, company=?, bio=?, photo=?, location=? WHERE id=1'
    );
    $stmt->execute([
        $input['person']['name'], $input['person']['title'], $input['person']['company'],
        $input['person']['bio'], $input['person']['photo'], $input['person']['location'],
    ]);

    $stmt = $pdo->prepare('UPDATE contact SET phone=?, whatsapp=?, email=? WHERE id=1');
    $stmt->execute([
        $input['contact']['phone'], $input['contact']['whatsapp'], $input['contact']['email'],
    ]);

    $pdo->exec('DELETE FROM links');
    $stmt = $pdo->prepare('INSERT INTO links (label, url, sort_order) VALUES (?, ?, ?)');
    foreach ($input['links'] as $i => $link) {
        $stmt->execute([$link['label'], $link['url'], $i]);
    }

    $pdo->exec('DELETE FROM portfolio');
    $stmt = $pdo->prepare('INSERT INTO portfolio (title, description, image, link, sort_order) VALUES (?, ?, ?, ?, ?)');
    foreach ($input['portfolio'] as $i => $item) {
        $stmt->execute([$item['title'], $item['description'], $item['image'], $item['link'], $i]);
    }

    $pdo->commit();
    echo json_encode(['status' => 'saved']);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'detail' => $e->getMessage()]);
}
*/
