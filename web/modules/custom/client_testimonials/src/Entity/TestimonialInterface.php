<?php

namespace Drupal\client_testimonials\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Testimonial entities.
 */
interface TestimonialInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface, EntityPublishedInterface {

  /**
   * Gets the Testimonial creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Testimonial.
   */
  public function getCreatedTime();

  /**
   * Sets the Testimonial creation timestamp.
   *
   * @param int $timestamp
   *   The Testimonial creation timestamp.
   *
   * @return \Drupal\client_testimonials\Entity\TestimonialInterface
   *   The called Testimonial entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Testimonial submitted date timestamp.
   *
   * @return int
   *   Submission timestamp of the Testimonial.
   */
  public function getSubmittedDate();

}
