<?php

/**
 * @file
 * Script to hide title on existing testimonial block instances.
 *
 * Run with: drush scr hide_title.php
 */

use Drupal\block\Entity\Block;

// Find all instances of our testimonial block
$testimonial_blocks = \Drupal::entityTypeManager()
  ->getStorage('block')
  ->loadByProperties(['plugin' => 'testimonial_block']);

if (empty($testimonial_blocks)) {
  echo "No testimonial blocks found.\n";
  exit;
}

$updated = 0;
foreach ($testimonial_blocks as $block) {
  echo "Updating block: " . $block->id() . "\n";

  // Hide the label
  $block->set('settings', array_merge(
    $block->get('settings'),
    ['label_display' => FALSE]
  ));

  $block->save();
  $updated++;
}

echo "$updated testimonial blocks updated to hide title.\n";
echo "Clear cache to see changes with: drush cr\n";
