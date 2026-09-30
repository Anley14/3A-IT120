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

INSERT IGNORE INTO admins
  (first_name, last_name, username, password_hash, admin_title, must_change_password)
VALUES
  ('Sol', 'Solis', 'admin', '$2y$10$Qhc1e24D85sjrG3b.mKjU.z2pFnInbF7X886XVUuwDjKALqXD8Dmu', 'IT Head', 1),
  ('Katherine', 'Sinagaraw', 'Kitty Kat 16', '$2y$10$BqiDPdt33Ym3eq4HA6iiuecJqO8aDwjC1bacCrnnLwq3pISDDNfO.', 'Founder', 1);
