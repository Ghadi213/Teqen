-- Teqen database structure
-- Import this file in phpMyAdmin (Import tab) or run: mysql -u root -p teqen_db < database.sql

CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  password      VARCHAR(255) NOT NULL,                -- hashed with password_hash()
  department    VARCHAR(100),
  role          ENUM('normal', 'digital') NOT NULL DEFAULT 'normal',  -- digital = IT staff
  reset_token   VARCHAR(255) NULL,
  token_expiry  DATETIME NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE reports (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT NOT NULL,
  title        VARCHAR(255) NOT NULL,
  description  TEXT NOT NULL,
  urgency      ENUM('low', 'medium', 'high') NOT NULL,
  image_path   VARCHAR(255) NULL,
  status       ENUM('new', 'processing', 'done') NOT NULL DEFAULT 'new',
  handled_by   VARCHAR(100) NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE messages (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  report_id    INT NOT NULL,
  sender_id    INT NOT NULL,
  sender_type  VARCHAR(20) NOT NULL,                   -- normal or digital
  message      TEXT NOT NULL,
  seen_by      VARCHAR(255) NOT NULL DEFAULT '',        -- user ids who read it, comma separated
  timestamp    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (report_id) REFERENCES reports(id),
  FOREIGN KEY (sender_id) REFERENCES users(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
