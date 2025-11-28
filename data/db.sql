-- Schema for demo ecommerce
CREATE DATABASE IF NOT EXISTS ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sku VARCHAR(50) UNIQUE,
  nome VARCHAR(255) NOT NULL,
  descricao TEXT,
  preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  imagem TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_cliente VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  endereco TEXT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'Pendente',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  nome VARCHAR(255) NOT NULL,
  quantidade INT NOT NULL DEFAULT 1,
  preco DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Optional: index for product name/search
CREATE INDEX idx_products_nome ON products(nome);
