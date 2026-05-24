CREATE DATABASE IF NOT EXISTS minishop_phase2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE minishop_phase2;

DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(60) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_categories FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_order_items_orders FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_items_products FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@gmail.com', '$2y$12$A2LQskQjAzCcwyGokyb1/.ykz6ncz9PEriqQoVFW53xqNmqIkfovO', 'admin'),
('User', 'user@gmail.com', '$2y$12$pemfuGjByNBSA7OLlLJc3eQEXFWLj.uckrDMFzUcd45JktJn045XO', 'user');
-- Password për të dy llogaritë testuese: admin123 / user123

INSERT INTO categories (name, slug) VALUES
('Pijet (Drinks)', 'drinks'),
('Embelsirat (Sweet)', 'sweet'),
('Te kripura (Salt)', 'salt');

INSERT INTO products (category_id, name, price, image) VALUES
(1, 'Coca Cola', 0.60, 'https://tse4.mm.bing.net/th/id/OIP.gOSWD16OMZ6vAMmKT8ltUwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3'),
(1, 'Fanta', 0.60, 'https://tse3.mm.bing.net/th/id/OIP.UgCfYB2b2LHw6jLi6pm_GwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3'),
(1, 'Ujë', 0.30, 'https://i5.walmartimages.com/asr/83568193-2418-40f0-8367-4fd80481e2f8_1.fc5284020d9e9b9bcd45d777a9e62bb5.jpeg'),
(1, 'Lëng Portokalli', 0.70, 'https://tse4.mm.bing.net/th/id/OIP.cnKmGyy2Kdtr6YxvNGh-xQHaHa?rs=1&pid=ImgDetMain&o=7&rm=3'),
(1, 'Sprite', 0.60, 'https://tse1.mm.bing.net/th/id/OIP.QCwBuHCN8aYIl7vJcSg3hgAAAA?w=341&h=929&rs=1&pid=ImgDetMain&o=7&rm=3'),
(2, 'Bakllava', 2.50, 'https://m.media-amazon.com/images/I/719q-rWf6bL._AC_SL1500_.jpg'),
(2, 'Trilece', 2.00, 'https://glossy.espreso.co.rs/data/images/2023/02/19/15/355876_trileceshutterstock-2145843005_ff.jpg?ver=1676816929'),
(3, 'Kikirika', 0.80, 'https://tse3.mm.bing.net/th/id/OIP.HdWX48IfdUf6PU-NVLkYzAHaDo?rs=1&pid=ImgDetMain&o=7&rm=3'),
(3, 'Biskota te kripura', 1.10, 'https://img.freepik.com/premium-photo/small-salted-biscuits_1048944-3064212.jpg'),
(1, 'Fanta exotic',0.60, 'https://unclefood.com/cdn/shop/files/fanta-exotic-330ml_1.jpg?v=1706807136'),
(1, 'Golden Eagle', 0.60, 'https://goldeneagle-ks.com/wp-content/uploads/2023/11/Golden-Eagle-250ml.png'),
(3, 'Doritos', 1.50, 'https://mercatoronline.si/img/cache/products/9694/product_medium_image/00521703.jpg'),
(2,'Biskota',1.20, 'https://www.apotekaherba.rs/uploads/store/products/images/keks-integralni-cokolada-cioccolato-280g-6394f9cf679b0.webp');
