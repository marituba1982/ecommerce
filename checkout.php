<?php
require __DIR__ . '/php/templates/header.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo '<p>Seu carrinho está vazio — <a href="index.php">voltar</a></p>';
    require __DIR__ . '/php/templates/footer.php';
    exit;
}

$total = 0; foreach ($cart as $i) $total += $i['preco']*$i['qty'];

?>

<section>
  <h2>Finalizar Compra</h2>
  <div class="checkout-grid">
    <div>
      <form action="<?php echo app_path('/php/place_order.php'); ?>" method="post" class="checkout-form">
        <label>Nome completo <input name="nome_cliente" required></label>
        <label>Email <input name="email" type="email" required></label>
        <label>Endereço <textarea name="endereco" required></textarea></label>
        <input type="hidden" name="total" value="<?php echo htmlspecialchars($total); ?>">
        <button class="btn-submit">Confirmar pedido — R$ <?php echo number_format($total,2,',','.'); ?></button>
      </form>
    </div>
    <aside>
      <h3>Resumo</h3>
      <?php foreach ($cart as $ci): ?>
        <div><?php echo htmlspecialchars($ci['nome']); ?> x <?php echo (int)$ci['qty']; ?> — R$ <?php echo number_format($ci['preco']*$ci['qty'],2,',','.'); ?></div>
      <?php endforeach; ?>
      <div style="margin-top:1rem;font-weight:bold">Total: R$ <?php echo number_format($total,2,',','.'); ?></div>
    </aside>
  </div>
</section>

<?php require __DIR__ . '/php/templates/footer.php'; ?>
