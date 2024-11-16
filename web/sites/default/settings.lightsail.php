<?php

// Database connection settings
$databases = [
  'default' => [
    'default' => [
      'database' => getenv('DRUPAL_DB_NAME') ?: 'drupal',
      'username' => getenv('DRUPAL_DB_USER') ?: 'drupaluser',
      'password' => getenv('DRUPAL_DB_PASSWORD') ?: 'your_password',
      'host' => getenv('DRUPAL_DB_HOST') ?: 'db',
      'port' => '',
      'driver' => 'mysql',
      'prefix' => '',
    ],
  ],
];

$config['system.logging']['error_level'] = 'verbose';

// Salt for secure hashing of passwords
$settings['hash_salt'] = getenv('DRUPAL_HASH_SALT') ?: 'some-random-hash-value';

// File system paths and permissions
$settings['file_private_path'] = '/var/www/html/sites/default/files/private';
$settings['file_public_path'] = 'sites/default/files';
$settings['file_temp_path'] = '/tmp';

// Trusted host patterns (you should update these based on your domain or IP)
$settings['trusted_host_patterns'] = [
  '^localhost$',
  '^127\.0\.0\.1$',
  '^54\.205\.25\.75$',
  '^ericsdrupalportfolio.com$',
  '^www\.ericsdrupalportfolio\.com$',
];

// Config sync directory (if using configuration management)
// $config_directories[CONFIG_SYNC_DIRECTORY] = '/var/www/html/sites/default/files/config/sync';
$settings['config_sync_directory'] = '/var/www/html/sites/default/files/config/sync';

// Allow installation scripts to write the settings file when needed.
$settings['config_sync_directory'] = 'sites/default/files/config/sync';
 