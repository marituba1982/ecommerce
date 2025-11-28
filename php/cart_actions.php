<?php
// Session-based cart handler: add, update, remove
require_once __DIR__ . '/db.php';
// allow base path redirects when app is in subfolder
$cfg = require __DIR__ . '/config.php';
$BASE_PATH = rtrim($cfg['base_path'] ?? '', '/');
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . (($BASE_PATH === '' ? '/' : $BASE_PATH) . '/index.php'));
    exit;
}

$action = $_POST['action'] ?? 'add';

function findProduct($id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, sku, nome, preco, imagem FROM products WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

if ($action === 'add') {
    $id = (int)($_POST['product_id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    $product = findProduct($id);
    if (!$product) {
        $_SESSION['flash_error'] = 'Produto não encontrado.';
        header('Location: ' . (($BASE_PATH === '' ? '/' : $BASE_PATH) . '/index.php'));
        exit;
    }

    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = ['id'=>$product['id'],'nome'=>$product['nome'],'preco'=>$product['preco'],'imagem'=>$product['imagem'],'qty'=>0];
    }
    $_SESSION['cart'][$id]['qty'] += $qty;

    header('Location: ' . (($BASE_PATH === '' ? '/' : $BASE_PATH) . '/carrinho.php'));
    exit;

} elseif ($action === 'update') {
    $id = (int)($_POST['product_id'] ?? 0);
    $qty = max(0, (int)($_POST['qty'] ?? 0));
    if (isset($_SESSION['cart'][$id])) {
        if ($qty <= 0) unset($_SESSION['cart'][$id]);
        else $_SESSION['cart'][$id]['qty'] = $qty;
    }
    header('Location: ' . (($BASE_PATH === '' ? '/' : $BASE_PATH) . '/carrinho.php'));
    exit;

} elseif ($action === 'remove') {
    $id = (int)($_POST['product_id'] ?? 0);
    if (isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
    header('Location: ' . (($BASE_PATH === '' ? '/' : $BASE_PATH) . '/carrinho.php'));
    exit;

} else {
    header('Location: ' . (($BASE_PATH === '' ? '/' : $BASE_PATH) . '/index.php'));
    exit;
}
