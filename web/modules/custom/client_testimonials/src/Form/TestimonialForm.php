<?php

namespace Drupal\client_testimonials\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Form controller for Testimonial edit forms.
 */
class TestimonialForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    // Add a separate permission for publishing.
    $form['status']['#access'] = $this->currentUser()->hasPermission('publish testimonials');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $status = $entity->save();

    $link = $entity->toLink($this->t('View'))->toString();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => $link];

    if ($status == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New testimonial %label has been created.', $message_arguments));
      $this->logger('client_testimonials')->notice('Created new testimonial %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The testimonial %label has been updated.', $message_arguments));
      $this->logger('client_testimonials')->notice('Updated testimonial %label.', $logger_arguments);
    }

    // Send notification if testimonial status has changed.
    if (isset($form['status']['#default_value']) && $form['status']['#default_value'] != $entity->isPublished()) {
      if ($entity->isPublished()) {
        $this->sendApprovalNotification($entity);
      }
    }

    $form_state->setRedirect('entity.testimonial.collection');
  }

  /**
   * Send approval notification to the user.
   *
   * @param \Drupal\client_testimonials\Entity\Testimonial $testimonial
   *   The testimonial entity.
   */
  protected function sendApprovalNotification($testimonial) {
    // Only send email if user notifications are enabled.
    if (!\Drupal::config('client_testimonials.settings')->get('enable_user_notifications')) {
      return;
    }

    $user = $testimonial->getOwner();
    // Skip if no valid email.
    if (!$user || !$user->getEmail()) {
      return;
    }

    $params = [
      'name' => $testimonial->get('client_name')->value,
    ];

    $mail_manager = \Drupal::service('plugin.manager.mail');
    $mail_manager->mail(
      'client_testimonials',
      'testimonial_approved',
      $user->getEmail(),
      $user->getPreferredLangcode(),
      $params
    );
  }

}
