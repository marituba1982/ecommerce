<?php
require_once __DIR__ . '/db.php';
$cfg = require __DIR__ . '/config.php';
$BASE_PATH = rtrim($cfg['base_path'] ?? '', '/');
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . (($BASE_PATH === '' ? '/' : $BASE_PATH) . '/index.php'));
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    die('Carrinho vazio');
}

$nome = trim($_POST['nome_cliente'] ?? '');
$email = trim($_POST['email'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$total = (float)($_POST['total'] ?? 0);

if ($nome==='' || $email==='' || $endereco==='') {
    die('Dados inválidos.');
}

$pdo = getPDO();
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT INTO orders (nome_cliente,email,endereco,total) VALUES (:nome,:email,:endereco,:total)');
    $stmt->execute([':nome'=>$nome,':email'=>$email,':endereco'=>$endereco,':total'=>$total]);
    $orderId = (int)$pdo->lastInsertId();

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, nome, quantidade, preco) VALUES (:order_id,:product_id,:nome,:quantidade,:preco)');
    foreach ($cart as $ci) {
        $itemStmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $ci['id'],
            ':nome' => $ci['nome'],
            ':quantidade' => $ci['qty'],
            ':preco' => $ci['preco']
        ]);
    }

    $pdo->commit();
    // clear cart
    unset($_SESSION['cart']);

    header('Location: ' . (($BASE_PATH === '' ? '/' : $BASE_PATH) . '/order_confirmation.php?id=' . $orderId));
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo '<h3>Erro ao registrar pedido</h3>' . htmlspecialchars($e->getMessage());
    exit;
}
