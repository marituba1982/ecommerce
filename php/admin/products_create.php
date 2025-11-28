<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../templates/header.php';

?>

<section>
  <h2>Adicionar produto</h2>
  <form method="post" action="products_actions.php" class="admin-form">
    <input type="hidden" name="action" value="create">
    <label>SKU: <input name="sku" required></label>
    <label>Nome: <input name="nome" required></label>
    <label>Descrição: <textarea name="descricao"></textarea></label>
    <label>Preço: <input name="preco" type="number" step="0.01" min="0" required></label>
    <label>Imagem (URL): <input name="imagem" type="url" ></label>
    <div>
      <button class="btn-submit">Salvar</button>
      <a class="btn-outline" href="products_list.php">Cancelar</a>
    </div>
  </form>
</section>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
