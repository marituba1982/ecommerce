<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../templates/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo "<p>ID inválido.</p>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$stmt->execute([':id' => $id]);
$product = $stmt->fetch();
if (!$product) {
    echo "<p>Produto não encontrado.</p>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

?>

<section>
  <h2>Editar produto #<?php echo (int)$product['id']; ?></h2>
  <form method="post" action="products_actions.php" class="admin-form">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
    <label>SKU: <input name="sku" required value="<?php echo htmlspecialchars($product['sku']); ?>"></label>
    <label>Nome: <input name="nome" required value="<?php echo htmlspecialchars($product['nome']); ?>"></label>
    <label>Descrição: <textarea name="descricao"><?php echo htmlspecialchars($product['descricao']); ?></textarea></label>
    <label>Preço: <input name="preco" type="number" step="0.01" min="0" required value="<?php echo htmlspecialchars($product['preco']); ?>"></label>
    <label>Imagem (URL): <input name="imagem" type="url" value="<?php echo htmlspecialchars($product['imagem']); ?>"></label>
    <div>
      <button class="btn-submit">Atualizar</button>
      <a class="btn-outline" href="products_list.php">Cancelar</a>
    </div>
  </form>
</section>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
