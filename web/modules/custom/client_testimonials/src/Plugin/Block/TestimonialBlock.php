<?php

namespace Drupal\client_testimonials\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block to display testimonials.
 *
 * @Block(
 *   id = "testimonial_block",
 *   admin_label = @Translation("Client Testimonials"),
 *   category = @Translation("Client Testimonials")
 * )
 */
class TestimonialBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new TestimonialBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'max_testimonials' => 3,
      'random_order' => TRUE,
      'display_image' => TRUE,
      'label_display' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['max_testimonials'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum number of testimonials to display'),
      '#default_value' => $config['max_testimonials'],
      '#min' => 1,
      '#max' => 10,
      '#required' => TRUE,
    ];

    $form['random_order'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display testimonials in random order'),
      '#default_value' => $config['random_order'],
    ];

    $form['display_image'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display client images'),
      '#default_value' => $config['display_image'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['max_testimonials'] = $form_state->getValue('max_testimonials');
    $this->configuration['random_order'] = $form_state->getValue('random_order');
    $this->configuration['display_image'] = $form_state->getValue('display_image');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $max_testimonials = $config['max_testimonials'];

    // Query for published testimonials
    $query = $this->entityTypeManager->getStorage('testimonial')->getQuery()
      ->condition('status', 1)
      ->accessCheck(TRUE);

    // Always sort by submitted date for consistent results
    $query->sort('submitted_date', 'DESC');

    // Don't limit query if we need to randomize later
    if (!$config['random_order']) {
      $query->range(0, $max_testimonials);
    }

    $testimonial_ids = $query->execute();
    $testimonials = [];

    if (!empty($testimonial_ids)) {
      $testimonials = $this->entityTypeManager->getStorage('testimonial')->loadMultiple($testimonial_ids);

      // Handle random ordering in PHP instead of SQL
      if ($config['random_order']) {
        // Convert to array and shuffle
        $testimonials_array = array_values($testimonials);
        shuffle($testimonials_array);
        // Limit after shuffling
        $testimonials_array = array_slice($testimonials_array, 0, $max_testimonials);
        $testimonials = $testimonials_array;
      }
    }

    // Create a container for the testimonials
    $build = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['bd-testimonial__area', 'section-space'],
        'id' => 'testimonial',
      ],
      '#attached' => [
        'library' => [
          'client_testimonials/testimonials',
        ],
      ],
      '#cache' => [
        'tags' => ['testimonial_list'],
      ],
    ];

    // Ensure we have the necessary CSS and JS libraries
    if (file_exists(DRUPAL_ROOT . '/themes/portfolio/assets/js/plugins/swiper.min.js')) {
      $build['#attached']['library'][] = 'portfolio/swiper';
    }
    if (file_exists(DRUPAL_ROOT . '/themes/portfolio/assets/js/main.js')) {
      $build['#attached']['library'][] = 'portfolio/global-scripts';
    }

    $testimonials_markup = '';
    if (!empty($testimonials)) {
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

        // Get image if available
        $image_markup = '';
        $image_url = '/themes/portfolio/assets/images/testimonial/testimonial-02.png'; // Default image
        if ($config['display_image']) {
          foreach (['client_image', 'image', 'field_client_image', 'field_image'] as $field_name) {
            if ($testimonial->hasField($field_name) && !$testimonial->get($field_name)->isEmpty()) {
              $image_render = $this->entityTypeManager->getViewBuilder('file')
                ->view($testimonial->get($field_name)->entity, 'testimonial_thumb');
              $image_markup = \Drupal::service('renderer')->renderPlain($image_render);
              // Extract image URL if we can
              $file_uri = $testimonial->get($field_name)->entity->getFileUri();
              $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($file_uri);
              break;
            }
          }
        }

        // Build testimonial slide markup
        $testimonials_markup .= '
          <div class="swiper-slide">
            <div class="testimonial__content">
              <div class="testimonial__shape-icon">
                <img src="/themes/portfolio/assets/images/shape/testimonial-quote-02.png" alt="quote">
              </div>
              <div class="testimonial__text">
                <p>' . nl2br($text) . '</p>
              </div>
              <div class="testimonial__avatar-info">
                <h6 class="testimonial__avatar-title">' . $name . '</h6>
                <span class="testimonial__avatar-designation">' . $date . '</span>
              </div>
            </div>
          </div>';
      }
    }

    // Build the testimonial section with carousel
    $build['testimonial_section'] = [
      '#type' => 'markup',
      '#markup' => '
        <div class="container">
          <div class="row g-5">
            <div class="col-xl-6 col-lg-6">
              <div class="section__title-wrapper section__title-space">
                <span class="section__subtitle bg-field">TESTIMONIAL</span>
                <h2 class="section__title">What They Say</h2>
              </div>
            </div>
          </div>
          <div class="testimonial__wrapper style-two wow fadeInUp" data-wow-delay=".3s">
            <div class="row align-items-center g-5">
              <div class="col-lg-3 col-md-5">
                <div class="testimonial__thumb">
                  <img src="/themes/portfolio/assets/images/testimonial/testimonial-02.png" alt="testimonial">
                  <div class="testimonial__shape">
                    <img class="testimonial__shape-dot" src="/themes/portfolio/assets/images/shape/dot-shape.png" alt="dots">
                  </div>
                </div>
              </div>
              <div class="col-lg-9 col-md-7">
                <div class="testimonial__item">
                  <div class="swiper testimonial__active">
                    <div class="swiper-wrapper">
                      ' . $testimonials_markup . '
                    </div>
                  </div>
                  <!-- Navigation buttons -->
                  <div class="testimonial__navigation d-none d-sm-block">
                    <button class="testimonial__button-prev circle-btn slider__nav-btn">
                      <i class="fa-regular fa-arrow-left-long"></i>
                    </button>
                    <button class="testimonial__button-next circle-btn ml-5 slider__nav-btn">
                      <i class="fa-regular fa-arrow-right-long"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      ',
    ];

    // Add Submit CTA section
    $build['submit_cta'] = [
      '#type' => 'markup',
      '#markup' => '
        <div class="container mt-5">
          <div class="testimonials-block__submit-section">
            <div class="testimonials-block__submit-content">
              <h3 class="testimonials-block__submit-title">Share Your Experience</h3>
              <p class="testimonials-block__submit-text">I value your feedback! If you\'ve worked with me, I\'d love to hear about your experience.</p>
              <a href="/testimonials/submit" class="testimonials-block__submit-button">Submit Your Testimonial</a>
            </div>
          </div>
        </div>
      ',
    ];

    // Add inline JS to initialize the Swiper carousel right after DOM content loads
    $build['#attached']['html_head'][] = [
      [
        '#type' => 'html_tag',
        '#tag' => 'script',
        '#value' => 'document.addEventListener("DOMContentLoaded", function() {
          setTimeout(function() {
            if (typeof Swiper !== "undefined") {
              var testimonialSwiper = new Swiper(".testimonial__active", {
                loop: true,
                freeMode: false,
                slidesPerView: 1,
                spaceBetween: 30,
                centeredSlides: false,
                autoplay: {
                  delay: 5000,
                  disableOnInteraction: false,
                },
                speed: 1000,
                navigation: {
                  nextEl: ".testimonial__button-next",
                  prevEl: ".testimonial__button-prev",
                },
                pagination: {
                  el: ".bd-swiper-dot",
                  clickable: true,
                },
              });

              // Add manual navigation functionality
              document.querySelector(".testimonial__button-next").addEventListener("click", function() {
                testimonialSwiper.slideNext();
              });

              document.querySelector(".testimonial__button-prev").addEventListener("click", function() {
                testimonialSwiper.slidePrev();
              });

              console.log("Testimonial Swiper initialized");
            } else {
              console.error("Swiper library not loaded");
            }
          }, 500); // Small delay to ensure everything is loaded
        });',
      ],
      'testimonial-swiper-js',
    ];

    return $build;
  }

}
