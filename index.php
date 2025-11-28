<?php
require __DIR__ . '/php/db.php';
require __DIR__ . '/php/templates/header.php';

try {
    $pdo = getPDO();
    $stmt = $pdo->query('SELECT id, sku, nome, descricao, preco, imagem FROM products ORDER BY created_at DESC');
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
}

?>

<section id="hero">
  <div class="container">
    <h2>Produtos em destaque</h2>
    <p>Confira os produtos do catálogo. Use a área admin para gerenciar produtos.</p>
  </div>
</section>

<section id="cards" class="container product-grid">
  <?php if (empty($products)): ?>
    <div class="notice">Nenhum produto encontrado — execute <code>/php/init_db.php</code> para preparar o banco de dados.</div>
  <?php endif; ?>

  <?php foreach ($products as $p): ?>
    <article class="product-card">
      <img src="<?php echo htmlspecialchars($p['imagem']); ?>" alt="<?php echo htmlspecialchars($p['nome']); ?>"/>
      <h3><?php echo htmlspecialchars($p['nome']); ?></h3>
      <p class="desc"><?php echo htmlspecialchars($p['descricao']); ?></p>
      <div class="meta">
        <strong>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></strong>
        <form method="post" action="<?php echo app_path('/php/cart_actions.php'); ?>" class="add-to-cart">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>">
          <label>Qtd: <input type="number" min="1" name="qty" value="1"></label>
          <button type="submit">Adicionar ao carrinho</button>
        </form>
      </div>
    </article>
  <?php endforeach; ?>
</section>

<?php require __DIR__ . '/php/templates/footer.php'; ?>
