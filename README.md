# Loja Demo — Projeto Final (PHP + PDO + MySQL)

Resumo
-------
Este projeto é uma implementação final de um sistema de comércio eletrônico leve, com integração a um SGBD relacional usando PHP e PDO. Ele demonstra operações CRUD de produtos, carrinho de compras, finalização de pedido e gravação de pedidos em banco de dados — tudo com prepared statements e transações para segurança.

Funcionalidades implementadas
-----------------------------
- Integração com banco MySQL (arquivo de schema em `data/db.sql`) usando PDO
- Operações CRUD completas para produtos (admin)
- Exibição de catálogo de produtos (página inicial `index.php`)
- Carrinho de compras em sessão (add / update / remove) usando `php/cart_actions.php` e `carrinho.php`
- Finalização de pedido com gravação em `orders` e `order_items` (`php/place_order.php`)
- Prepared statements para todas as operações com entradas do usuário
- Uso de transações para garantir atomicidade no registro de pedidos
- Responsividade e integração front-end (HTML5 / CSS3 / JavaScript)

Estrutura principal de arquivos
------------------------------
- php/
  - config.php — configurações de conexão (db_host, db_name, user, pass)
  - db.php — helper para obter instância PDO (atributos seguros)
  - init_db.php — script web para criar o schema e semear dados iniciais
  - cart_actions.php — endpoint para manipular carrinho via sessão
  - place_order.php — grava pedido e items com transação
  - templates/header.php, footer.php — layout simples reusável
  - admin/* — CRUD de produtos (create, edit, delete, list)

Banco de dados
-------------
O schema está em `data/db.sql`. Tabelas principais:
- products (id, sku, nome, descricao, preco, imagem)
- orders (id, nome_cliente, email, endereco, total, status)
- order_items (id, order_id, product_id, nome, quantidade, preco)

Segurança e boas práticas
------------------------
- Prepared statements (PDO::prepare / execute) para todas as operações com entrada do usuário — evita injeção SQL
- PDO configurado com ERRMODE_EXCEPTION e FETCH_ASSOC
- Uso de transações ao inserir pedido + itens — garante atomicidade e consistência
- Validações simples nos endpoints (checar campos obrigatórios, tipos)

Instalação / execução local
---------------------------
Requisitos: PHP 7.4+ e MySQL (XAMPP, MAMP, Laragon ou MySQL local).

1) Copie o projeto para o diretório público do seu servidor (ex.: `C:/xampp/htdocs/loja-demo`)
2) Configure `php/config.php` se necessário (usuário/senha/host)
3) Abra no navegador `http://localhost/LOJA_PATH/php/init_db.php` para criar as tabelas e semear produtos (ou rode `mysql < data/db.sql` e em seguida execute o seeder manualmente)
4) Acesse `http://localhost/LOJA_PATH/index.php` para ver o catálogo
5) Admin: `http://localhost/LOJA_PATH/php/admin/products_list.php` — gerencie produtos

Observação: se preferir rodar com o servidor embutido do PHP para teste rápido, faça:
```bash
cd /path/to/project
php -S localhost:8000
```
Depois, em outro terminal, importe o SQL e aponte o navegador para `http://localhost:8000/php/init_db.php`.

Demonstração rápida de fluxo (exemplo)
------------------------------------
1. Vá em `/php/admin/products_list.php`, inclua um novo produto.
2. Volte para `/index.php`, adicione item ao carrinho.
3. Acesse `/carrinho.php`, atualize quantidade e clique em `Finalizar compra`.
4. No checkout, preencha dados do cliente e submeta — o pedido será gravado em `orders` e `order_items`.

Decisões arquiteturais e nota final
----------------------------------
- Mantive uma separação simples entre templates e lógica; o projeto foi pensado para clareza didática e segurança básica, não para produção em larga escala.
- Recomendação: em produção, adicione autenticação, proteção CSRF, validação mais robusta, e evite expor scripts de inicialização no servidor público.

Contato
-------
Se quiser, posso gerar um roteiro de demonstração em vídeo ou testes funcionais adicionais (ex.: testes PHPUnit). 
