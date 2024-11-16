CREATE USER IF NOT EXISTS 'drupaluser'@'%' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON drupal.* TO 'drupaluser'@'%';
FLUSH PRIVILEGES;
