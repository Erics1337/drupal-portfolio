<?php

namespace Drupal\client_testimonials\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Testimonial entities.
 */
class TestimonialViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional views data for testimonial fields.
    $data['testimonial']['table']['base'] = [
      'field' => 'id',
      'title' => $this->t('Testimonials'),
      'help' => $this->t('The testimonial entity ID.'),
    ];

    $data['testimonial']['status']['field']['id'] = 'boolean';
    $data['testimonial']['status']['filter']['id'] = 'boolean';
    $data['testimonial']['status']['filter']['label'] = $this->t('Published');
    $data['testimonial']['status']['filter']['type'] = 'yes-no';

    $data['testimonial']['submitted_date']['filter']['id'] = 'date';
    $data['testimonial']['submitted_date']['sort']['id'] = 'date';

    return $data;
  }

}
