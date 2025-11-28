<?php
// PDO connection helper
$config = require __DIR__ . '/config.php';

function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $c = require __DIR__ . '/config.php';
        $dsn = "mysql:host={$c['db_host']};dbname={$c['db_name']};charset={$c['db_charset']}";
        try {
            $pdo = new PDO($dsn, $c['db_user'], $c['db_pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            // Friendly local error; in production use a secure error page
            echo "<h2>Database connection error</h2>";
            echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
            exit;
        }
    }
    return $pdo;
}

?>
