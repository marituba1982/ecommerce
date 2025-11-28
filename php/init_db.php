<?php
// Simple DB initialization + seed script
set_time_limit(0);
require_once __DIR__ . '/config.php';

$cfg = require __DIR__ . '/config.php';

try {
    // connect to server without database in DSN to create DB if missing
    $dsn = "mysql:host={$cfg['db_host']};charset={$cfg['db_charset']}";
    $pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $sql = file_get_contents(__DIR__ . '/../data/db.sql');
    // split by semicolons to execute independent statements
    $stmts = array_filter(array_map('trim', explode(";", $sql)));
    foreach ($stmts as $stmt) {
        if ($stmt === '') continue;
        $pdo->exec($stmt);
    }

    echo "<p>Database created / schema applied.</p>";

    // now connect to the newly created database
    $dsnDb = "mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset={$cfg['db_charset']}";
    $pdoDb = new PDO($dsnDb, $cfg['db_user'], $cfg['db_pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // seed products from JSON
    $json = file_get_contents(__DIR__ . '/../data/products.json');
    $products = json_decode($json, true);
    $insert = $pdoDb->prepare("INSERT IGNORE INTO products (sku, nome, descricao, preco, imagem) VALUES (:sku,:nome,:descricao,:preco,:imagem)");
    foreach ($products as $p) {
        $sku = $p['id'];
        $insert->execute([
            ':sku' => $sku,
            ':nome' => $p['nome'],
            ':descricao' => $p['descricao'],
            ':preco' => $p['preco'],
            ':imagem' => $p['imagem'] ?? null
        ]);
    }

    echo "<p>Seeded products.</p>";
    echo "<p>Done. You can now open /index.php</p>";

} catch (PDOException $e) {
    echo "<h2>Initialization error</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    exit(1);
}
