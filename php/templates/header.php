<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$cfg = require __DIR__ . '/../config.php';
$BASE_PATH = rtrim($cfg['base_path'] ?? '', '/');

/**
 * Build a path for the app that works both when the project is served
 * from the webserver root and when served from a subfolder.
 * Usage: app_path('/css/styles.css')
 */
function app_path(string $path) {
  global $BASE_PATH;
  if ($BASE_PATH === '') {
    // when app is served from server document root use absolute path starting at '/'
    return '/' . ltrim($path, '/');
  }
  return $BASE_PATH . $path;
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Loja Demo</title>
  <link rel="stylesheet" href="<?php echo app_path('/css/styles.css'); ?>">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1><a href="<?php echo app_path('/index.php'); ?>">Loja Demo</a></h1>
      <nav>
        <a href="<?php echo app_path('/index.php'); ?>">Produtos</a>
        <a href="<?php echo app_path('/carrinho.php'); ?>">Carrinho (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'],'qty')) : 0; ?>)</a>
        <a href="<?php echo app_path('/php/admin/products_list.php'); ?>">Admin</a>
      </nav>
    </div>
  </header>
  <main class="container">
