<?php
/**
 * @file
 * Platform.sh example settings.php file for Drupal 10.
 */

// Default Drupal settings.
//
// These are already explained with detailed comments in Drupal's
// default.settings.php file.
//
// See https://api.drupal.org/api/drupal/sites!default!default.settings.php/10
$databases = [];
$config_directories = [];
$settings['update_free_access'] = FALSE;
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];
// @see https://www.drupal.org/node/3177901
$settings['state_cache'] = TRUE;

// The hash_salt should be a unique random value for each application.
// If left unset, the settings.platformsh.php file will attempt to provide one.
// You can also provide a specific value here if you prefer and it will be used
// instead. In most cases it's best to leave this blank on Platform.sh. You
// can configure a separate hash_salt in your settings.local.php file for
// local development.
// $settings['hash_salt'] = 'change_me';

$settings['file_public_path'] = 'sites/default/files';
$config['system.logging']['error_level'] = 'verbose';


// Automatic Platform.sh settings.
// Only include this file if the environment variable IS_PLATFORMSH is set.
if (getenv('IS_PLATFORMSH') == 'true' && file_exists($app_root . '/' . $site_path . '/settings.platformsh.php')) {
  include $app_root . '/' . $site_path . '/settings.platformsh.php';
}

// Automatically generated include for settings managed by ddev.
$ddev_settings = dirname(__FILE__) . '/settings.ddev.php';
if (getenv('IS_DDEV_PROJECT') == 'true' && is_readable($ddev_settings)) {
  require $ddev_settings;
}

// Include settings for Lightsail Docker environment if present.
$lightsail_settings = dirname(__FILE__) . '/settings.lightsail.php';
if (getenv('IS_LIGHTSAIL_ENV') == 'true' && is_readable($lightsail_settings)) {
  include $lightsail_settings;
}

// Local settings. These come last so that they can override anything.
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}
 