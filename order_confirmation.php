<?php
require __DIR__ . '/php/db.php';
require __DIR__ . '/php/templates/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo '<p>ID de pedido inválido</p>';
    require __DIR__ . '/php/templates/footer.php';
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
$stmt->execute([':id'=>$id]);
$order = $stmt->fetch();
if (!$order) {
    echo '<p>Pedido não encontrado</p>';
    require __DIR__ . '/php/templates/footer.php';
    exit;
}

$items = $pdo->prepare('SELECT * FROM order_items WHERE order_id = :id');
$items->execute([':id'=>$id]);
$items = $items->fetchAll();

?>

<section>
  <h2>Pedido confirmado — #<?php echo (int)$order['id']; ?></h2>
  <p>Obrigado <?php echo htmlspecialchars($order['nome_cliente']); ?>! Seu pedido foi registrado.</p>
  <h3>Resumo</h3>
  <ul>
    <?php foreach ($items as $it): ?>
      <li><?php echo htmlspecialchars($it['nome']); ?> x <?php echo (int)$it['quantidade']; ?> — R$ <?php echo number_format($it['preco']*$it['quantidade'],2,',','.'); ?></li>
    <?php endforeach; ?>
  </ul>
  <div><strong>Total: R$ <?php echo number_format($order['total'],2,',','.'); ?></strong></div>
  <p><a href="<?php echo app_path('/index.php'); ?>">Continuar comprando</a></p>
</section>

<?php require __DIR__ . '/php/templates/footer.php'; ?>
