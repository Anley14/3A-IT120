CREATE DATABASE IF NOT EXISTS sunson
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE sunson;

CREATE TABLE IF NOT EXISTS admins (
  admin_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(80) NOT NULL,
  middle_name VARCHAR(80) NULL,
  last_name VARCHAR(80) NOT NULL,
  birth_date DATE NULL,
  gender VARCHAR(40) NULL,
  phone_number VARCHAR(30) NULL,
  email VARCHAR(254) NULL,
  username VARCHAR(80) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  admin_title VARCHAR(100) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  must_change_password TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (admin_id),
  UNIQUE KEY uq_admins_username (username),
  UNIQUE KEY uq_admins_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(80) NOT NULL,
  middle_name VARCHAR(80) NULL,
  last_name VARCHAR(80) NOT NULL,
  birth_date DATE NULL,
  gender VARCHAR(40) NULL,
  country_code VARCHAR(5) NOT NULL DEFAULT '+63',
  phone_number VARCHAR(30) NULL,
  email VARCHAR(255) NOT NULL,
  username VARCHAR(80) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('customer', 'employee') NOT NULL DEFAULT 'customer',
  department VARCHAR(100) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS employees (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(80) NOT NULL,
  middle_name VARCHAR(80) NULL,
  last_name VARCHAR(80) NOT NULL,
  birth_date DATE NOT NULL,
  gender VARCHAR(40) NOT NULL,
  country_code VARCHAR(5) NOT NULL DEFAULT '+63',
  phone_number VARCHAR(30) NOT NULL,
  email VARCHAR(255) NOT NULL,
  username VARCHAR(80) NOT NULL,
  password VARCHAR(255) NOT NULL,
  department VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_employees_email (email),
  UNIQUE KEY uq_employees_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS quote_requests (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(160) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  service VARCHAR(30) NOT NULL,
  message TEXT NULL,
  customer_id INT UNSIGNED NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'New',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_quote_requests_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Customer solar product and service order requests. Prices are estimates and
-- orders are confirmed by Sun Son staff; this prototype does not process payment.
CREATE TABLE IF NOT EXISTS shop_orders (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id INT UNSIGNED NOT NULL,
  total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  status VARCHAR(30) NOT NULL DEFAULT 'New',
  service_address VARCHAR(255) NOT NULL,
  contact_phone VARCHAR(30) NOT NULL,
  notes TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_shop_orders_customer (customer_id),
  KEY idx_shop_orders_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS shop_order_items (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id INT UNSIGNED NOT NULL,
  product_id VARCHAR(80) NOT NULL,
  product_name VARCHAR(160) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  unit_price DECIMAL(12,2) NOT NULL,
  line_total DECIMAL(12,2) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_shop_order_items_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Local demo accounts for trying the separate customer and employee portals.
-- Demo password for both accounts: password
INSERT IGNORE INTO users
  (first_name, last_name, birth_date, gender, country_code, phone_number, email, username, password, role)
VALUES
  ('Demo', 'Customer', '1995-01-01', 'Prefer not to say', '+63', '9000000001', 'demo.customer@sunsonsolar.local', 'demo.customer', '$2y$10$mtQROjoek5la4LFLugwpJeDJy9qKxjCW8hP0bQYvlff6VJ0l1kOUu', 'customer');

INSERT IGNORE INTO employees
  (first_name, last_name, birth_date, gender, country_code, phone_number, email, username, password, department)
VALUES
  ('Demo', 'Employee', '1995-01-01', 'Prefer not to say', '+63', '9000000002', 'demo.employee@sunsonsolar.local', 'demo.employee', '$2y$10$mtQROjoek5la4LFLugwpJeDJy9qKxjCW8hP0bQYvlff6VJ0l1kOUu', 'IT');

INSERT IGNORE INTO admins
  (first_name, last_name, username, password_hash, admin_title, must_change_password)
VALUES
  ('Sol', 'Solis', 'admin', '$2y$10$Qhc1e24D85sjrG3b.mKjU.z2pFnInbF7X886XVUuwDjKALqXD8Dmu', 'IT Head', 1),
  ('Katherine', 'Sinagaraw', 'Kitty Kat 16', '$2y$10$BqiDPdt33Ym3eq4HA6iiuecJqO8aDwjC1bacCrnnLwq3pISDDNfO.', 'Founder', 1);
