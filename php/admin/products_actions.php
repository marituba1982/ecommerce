<?php
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products_list.php');
    exit;
}

$action = $_POST['action'] ?? '';
try {
    $pdo = getPDO();

    if ($action === 'create') {
        $sku = trim($_POST['sku'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $preco = (float)($_POST['preco'] ?? 0);
        $imagem = trim($_POST['imagem'] ?? '');

        if ($sku === '' || $nome === '') throw new Exception('SKU e Nome são obrigatórios.');

        $stmt = $pdo->prepare('INSERT INTO products (sku,nome,descricao,preco,imagem) VALUES (:sku,:nome,:descricao,:preco,:imagem)');
        $stmt->execute([
            ':sku' => $sku,
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':preco' => $preco,
            ':imagem' => $imagem ?: null
        ]);
        header('Location: products_list.php');
        exit;

    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $sku = trim($_POST['sku'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $preco = (float)($_POST['preco'] ?? 0);
        $imagem = trim($_POST['imagem'] ?? '');

        if (!$id || $sku === '' || $nome === '') throw new Exception('Dados inválidos.');

        $stmt = $pdo->prepare('UPDATE products SET sku=:sku, nome=:nome, descricao=:descricao, preco=:preco, imagem=:imagem WHERE id=:id');
        $stmt->execute([
            ':sku'=>$sku, ':nome'=>$nome, ':descricao'=>$descricao, ':preco'=>$preco, ':imagem'=>$imagem, ':id'=>$id
        ]);
        header('Location: products_list.php');
        exit;

    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) throw new Exception('ID inválido.');
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute([':id' => $id]);
        header('Location: products_list.php');
        exit;

    } else {
        throw new Exception('Ação desconhecida.');
    }

} catch (Exception $e) {
    // Minimal error handling for admin: show message and link back
    echo '<h3>Erro</h3>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="products_list.php">Voltar</a></p>';
}
