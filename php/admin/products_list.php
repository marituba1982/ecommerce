<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../templates/header.php';

$pdo = getPDO();
$stmt = $pdo->query('SELECT id, sku, nome, preco, created_at FROM products ORDER BY id DESC');
$products = $stmt->fetchAll();

?>

<section>
  <div class="admin-header">
    <h2>Produtos (Admin)</h2>
    <a class="btn-outline" href="products_create.php">Adicionar Produto</a>
  </div>

  <?php if (empty($products)): ?>
    <p>Nenhum produto cadastrado.</p>
  <?php else: ?>
    <table class="admin-table">
      <thead><tr><th>ID</th><th>SKU</th><th>Nome</th><th>Preço</th><th>Criado</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
          <td><?php echo (int)$p['id']; ?></td>
          <td><?php echo htmlspecialchars($p['sku']); ?></td>
          <td><?php echo htmlspecialchars($p['nome']); ?></td>
          <td>R$ <?php echo number_format($p['preco'],2,',','.'); ?></td>
          <td><?php echo htmlspecialchars($p['created_at']); ?></td>
          <td>
            <a href="products_edit.php?id=<?php echo (int)$p['id']; ?>">Editar</a>
            <form method="post" action="products_actions.php" style="display:inline" onsubmit="return confirm('Excluir produto?');">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
              <button type="submit" class="btn-remove">Excluir</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</section>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
