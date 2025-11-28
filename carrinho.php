<?php
require __DIR__ . '/php/templates/header.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) $total += $item['preco'] * $item['qty'];

?>

<section>
  <h2>Seu Carrinho</h2>

  <?php if (empty($cart)): ?>
    <p>Seu carrinho está vazio. <a href="index.php">Ver produtos</a></p>
  <?php else: ?>
    <div class="cart-list">
      <?php foreach ($cart as $id => $item): ?>
        <div class="cart-item">
          <div style="display:flex;align-items:center;gap:1rem;">
            <img class="cart-thumb" src="<?php echo htmlspecialchars($item['imagem']); ?>" alt="">
            <div class="cart-info">
              <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
              <p>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?> x <?php echo (int)$item['qty']; ?> = <strong>R$ <?php echo number_format($item['preco']*$item['qty'],2,',','.'); ?></strong></p>
            </div>
          </div>
          <div>
            <form method="post" action="<?php echo app_path('/php/cart_actions.php'); ?>">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="product_id" value="<?php echo (int)$id; ?>">
              <input type="number" name="qty" value="<?php echo (int)$item['qty']; ?>" min="0">
              <button class="btn-submit">Atualizar</button>
            </form>
            <form method="post" action="<?php echo app_path('/php/cart_actions.php'); ?>" style="margin-top:.5rem;">
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="product_id" value="<?php echo (int)$id; ?>">
              <button class="btn-remove">Remover</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="cart-summary">
      <div class="total-row">Total: R$ <?php echo number_format($total,2,',','.'); ?></div>
      <div class="actions">
        <a class="btn-outline" href="index.php">Continuar comprando</a>
        <a class="btn-submit" href="<?php echo app_path('/checkout.php'); ?>">Finalizar compra</a>
      </div>
    </div>
  <?php endif; ?>

</section>

<?php require __DIR__ . '/php/templates/footer.php'; ?>
