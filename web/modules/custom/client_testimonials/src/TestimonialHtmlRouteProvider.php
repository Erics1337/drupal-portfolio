<?php

namespace Drupal\client_testimonials;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Symfony\Component\Routing\Route;

/**
 * Provides routes for Testimonial entities.
 */
class TestimonialHtmlRouteProvider extends AdminHtmlRouteProvider {

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $collection = parent::getRoutes($entity_type);

    // Provide your custom entity routes here.
    $entity_type_id = $entity_type->id();

    // Add a settings form route.
    if ($settings_form_route = $this->getSettingsFormRoute($entity_type)) {
      $collection->add("entity.{$entity_type_id}.settings", $settings_form_route);
    }

    return $collection;
  }

  /**
   * Gets the settings form route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getSettingsFormRoute(EntityTypeInterface $entity_type) {
    if (!$entity_type->getBundleEntityType() && $route_name = $entity_type->getLinkTemplate('settings-form')) {
      $entity_type_id = $entity_type->id();
      $route = new Route("/admin/structure/{$entity_type_id}/settings");
      $route
        ->setDefaults([
          '_form' => 'Drupal\client_testimonials\Form\TestimonialSettingsForm',
          '_title' => "{$entity_type->getLabel()} settings",
        ])
        ->setRequirement('_permission', $entity_type->getAdminPermission())
        ->setOption('_admin_route', TRUE);

      return $route;
    }
  }

}
