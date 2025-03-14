<?php

namespace Drupal\client_testimonials\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\File\FileSystemInterface;

/**
 * Provides a form for submitting testimonials from the frontend.
 */
class TestimonialSubmissionForm extends FormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructs a new TestimonialSubmissionForm.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AccountInterface $current_user, FileSystemInterface $file_system) {
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('file_system')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'testimonial_submission_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['client_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your Name'),
      '#required' => TRUE,
      '#maxlength' => 255,
    ];

    $form['testimonial_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Your Testimonial'),
      '#required' => TRUE,
      '#description' => $this->t('Please share your experience or feedback.'),
      '#rows' => 5,
    ];

    $form['client_image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Your Photo (Optional)'),
      '#description' => $this->t('Upload a photo of yourself. Allowed extensions: jpg, jpeg, png'),
      '#upload_location' => 'public://testimonials/images/',
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg jpeg png'],
        'file_validate_size' => [1024 * 1024 * 2], // 2MB limit
      ],
      '#required' => FALSE,
    ];

    // Add AJAX support if specified in requirements.
    $form['#attributes']['class'][] = 'testimonial-submission-form';

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit Testimonial'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate testimonial text length.
    if (strlen($form_state->getValue('testimonial_text')) < 20) {
      $form_state->setErrorByName('testimonial_text', $this->t('Please provide a more detailed testimonial (at least 20 characters).'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    try {
      // Create testimonial entity.
      $testimonial = $this->entityTypeManager->getStorage('testimonial')->create([
        'client_name' => $values['client_name'],
        'testimonial_text' => $values['testimonial_text'],
        'user_id' => $this->currentUser->id(),
        'status' => FALSE, // Unpublished by default.
        'submitted_date' => \Drupal::time()->getRequestTime(),
      ]);

      // Handle image upload if provided.
      if (!empty($values['client_image'][0])) {
        $file = $this->entityTypeManager->getStorage('file')->load($values['client_image'][0]);
        if ($file) {
          // Make the file permanent.
          $file->setPermanent();
          $file->save();

          // Add file usage entry.
          \Drupal::service('file.usage')->add($file, 'client_testimonials', 'testimonial', $testimonial->id());

          // Set the file reference.
          $testimonial->set('client_image', $values['client_image'][0]);
        }
      }

      // Save the testimonial entity.
      $testimonial->save();

      // Send notification to admin if enabled.
      $this->sendAdminNotification($testimonial);

      // Set success message.
      $this->messenger()->addStatus($this->t('Thank you! Your testimonial has been submitted and will be reviewed by our team.'));

      // Redirect to the testimonials page.
      $form_state->setRedirect('client_testimonials.testimonial_list');
    }
    catch (\Exception $e) {
      $this->messenger()->addError($this->t('There was a problem submitting your testimonial. Please try again.'));
      $this->logger('client_testimonials')->error('Error submitting testimonial: @error', ['@error' => $e->getMessage()]);
    }
  }

  /**
   * Send notification to admin about the new testimonial.
   *
   * @param \Drupal\client_testimonials\Entity\Testimonial $testimonial
   *   The testimonial entity.
   */
  protected function sendAdminNotification($testimonial) {
    // Only send email if admin notifications are enabled.
    if (!\Drupal::config('client_testimonials.settings')->get('enable_admin_notifications')) {
      return;
    }

    $admin_email = \Drupal::config('client_testimonials.settings')->get('admin_email');
    if (empty($admin_email)) {
      $admin_email = \Drupal::config('system.site')->get('mail');
    }

    $params = [
      'name' => $testimonial->get('client_name')->value,
      'url' => Url::fromRoute('entity.testimonial.edit_form', ['testimonial' => $testimonial->id()], ['absolute' => TRUE])->toString(),
    ];

    $mail_manager = \Drupal::service('plugin.manager.mail');
    $mail_manager->mail(
      'client_testimonials',
      'testimonial_submission',
      $admin_email,
      \Drupal::languageManager()->getDefaultLanguage()->getId(),
      $params
    );
  }

}
