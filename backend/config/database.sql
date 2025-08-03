-- Tabel users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user'
);

-- Tabel products
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category VARCHAR(100) DEFAULT 'Umum',
  description TEXT,
  price INT NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255)
);

-- Tabel orders
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id VARCHAR(50),
  user_id INT NOT NULL,
  order_date DATETIME DEFAULT NOW(),
  status VARCHAR(20) DEFAULT 'pending',
  total INT DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel order_items
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  qty INT NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Tabel testimonials
CREATE TABLE testimonials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(100) NOT NULL,
  rating INT NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Contoh data produk
INSERT INTO products (name, category, description, price, stock, image) VALUES
('Pulpen', 'Alat Tulis', 'Pulpen tinta biru', 3000, 100, 'pulpen.jpg'),
('Pensil', 'Alat Tulis', 'Pensil kayu HB', 2000, 100, 'pensil.jpg'),
('Buku Tulis', 'Buku', 'Buku tulis 40 lembar', 5000, 50, 'buku.jpg');

CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(100),
    status VARCHAR(50),
    jumlah DECIMAL(12,2),
    waktu DATETIME
);

