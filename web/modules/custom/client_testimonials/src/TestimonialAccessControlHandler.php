<?php

namespace Drupal\client_testimonials;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Testimonial entity.
 */
class TestimonialAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\client_testimonials\Entity\TestimonialInterface $entity */
    switch ($operation) {
      case 'view':
        // Allow viewing if the testimonial is published or the user has the
        // 'review testimonials' permission.
        if ($entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'access content');
        }
        return AccessResult::allowedIfHasPermission($account, 'review testimonials');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'review testimonials');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'review testimonials');

      case 'publish':
        return AccessResult::allowedIfHasPermission($account, 'publish testimonials');

      default:
        // Unknown operation, no opinion.
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    // Any user with 'submit testimonials' permission can create testimonials.
    return AccessResult::allowedIfHasPermission($account, 'submit testimonials');
  }

}
