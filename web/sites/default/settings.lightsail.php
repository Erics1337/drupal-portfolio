<?php

// Set Redis as the default cache backend.
$settings['redis.connection']['interface'] = 'PhpRedis';
$settings['redis.connection']['host'] = 'drupal-portfolio-redis-1'; // The name of the Redis container
$settings['redis.connection']['port'] = 6379;

// Always set the default cache to use Redis
$settings['cache']['default'] = 'cache.backend.redis';

// Use Redis for caching bootstrap, discovery, and configuration
$settings['cache']['bins']['bootstrap'] = 'cache.backend.redis';
$settings['cache']['bins']['discovery'] = 'cache.backend.redis';
$settings['cache']['bins']['config'] = 'cache.backend.redis';

// Include Redis services file
$settings['container_yamls'][] = 'web/modules/contrib/redis/redis.services.yml';

// Set lock and queue services to use Redis
$settings['redis.connection']['lock'] = TRUE;
$settings['queue_default'] = 'queue.redis';

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
 