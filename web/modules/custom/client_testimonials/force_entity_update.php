<?php

/**
 * @file
 * Force update for testimonial entity schema.
 *
 * This script can be run with drush scr force_entity_update.php
 * or through PHP CLI.
 */

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// Bootstrap Drupal.
$autoloader = require_once 'autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$kernel->preHandle($request);
$container = $kernel->getContainer();

// Define our entity type.
$entity_type_id = 'testimonial';

// Get the entity type from the entity type manager.
$entity_type_manager = \Drupal::entityTypeManager();
$entity_definition_update_manager = \Drupal::entityDefinitionUpdateManager();

try {
  // Check if the entity type exists in the manager.
  if ($entity_type_manager->hasDefinition($entity_type_id)) {
    // Get the entity type definition.
    $entity_type = $entity_type_manager->getDefinition($entity_type_id);

    // Install the entity schema.
    $entity_definition_update_manager->installEntityType($entity_type);

    print "Successfully created the testimonial entity schema.\n";
  }
  else {
    print "Entity type $entity_type_id not found.\n";
  }
}
catch (Exception $e) {
  print "Error: " . $e->getMessage() . "\n";
}
