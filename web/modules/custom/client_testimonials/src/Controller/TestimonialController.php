<?php

namespace Drupal\client_testimonials\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Testimonial routes.
 */
class TestimonialController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a TestimonialController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Displays a list of published testimonials.
   *
   * @return array
   *   A render array for the testimonial list page.
   */
  public function listTestimonials() {
    // Query for published testimonials.
    $query = $this->entityTypeManager->getStorage('testimonial')->getQuery()
      ->condition('status', 1)
      ->sort('submitted_date', 'DESC')
      ->accessCheck(TRUE);
    $testimonial_ids = $query->execute();

    if (empty($testimonial_ids)) {
      return [
        '#markup' => $this->t('No testimonials available at this time.'),
      ];
    }

    $testimonials = $this->entityTypeManager->getStorage('testimonial')->loadMultiple($testimonial_ids);

    // Create a container for the testimonials
    $build = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['testimonials-container'],
      ],
      '#attached' => [
        'library' => [
          'client_testimonials/testimonials',
        ],
      ],
    ];

    // Build a simple list of testimonials directly
    foreach ($testimonials as $id => $testimonial) {
      // Get proper field values
      $text = '';
      if ($testimonial->hasField('testimonial_text') && !$testimonial->get('testimonial_text')->isEmpty()) {
        $text = $testimonial->get('testimonial_text')->value;
      }

      $name = '';
      if ($testimonial->hasField('client_name') && !$testimonial->get('client_name')->isEmpty()) {
        $name = $testimonial->get('client_name')->value;
      }

      $date = '';
      if ($testimonial->hasField('submitted_date') && !$testimonial->get('submitted_date')->isEmpty()) {
        $date = \Drupal::service('date.formatter')
          ->format($testimonial->get('submitted_date')->value, 'custom', 'F j, Y');
      }

      // Build a simple markup for the testimonial
      $build['testimonial_' . $id] = [
        '#type' => 'markup',
        '#markup' => '
          <div class="testimonial" style="max-width: 800px; margin: 0 auto 2rem auto; background-color: #1D1E1F; padding: 2rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="testimonial__content">
              <div class="testimonial__text" style="color: #ffffff; font-size: 1.1rem; margin-bottom: 1.5rem; font-style: italic; line-height: 1.7;">
                ' . nl2br($text) . '
              </div>
              <div class="testimonial__info" style="display: flex; border-top: 1px solid #232426; padding-top: 1.5rem; margin-top: 1.5rem;">
                <div class="testimonial__client" style="display: flex; flex-direction: column;">
                  <div class="testimonial__name" style="color: #ffffff; font-size: 1.2rem; font-weight: 600; margin-bottom: 0.25rem;">
                    ' . $name . '
                  </div>
                  <div class="testimonial__date" style="font-size: 0.85rem; color: #E2E4E6; opacity: 0.7;">
                    ' . $date . '
                  </div>
                </div>
              </div>
            </div>
          </div>
        ',
      ];
    }

    return $build;
  }

}
