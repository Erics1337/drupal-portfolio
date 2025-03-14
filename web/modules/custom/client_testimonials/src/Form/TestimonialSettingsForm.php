<?php

namespace Drupal\client_testimonials\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Settings form for Testimonial entities.
 */
class TestimonialSettingsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'testimonial_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['settings'] = [
      '#markup' => $this->t('Settings form for Testimonial entities. Manage field settings here.'),
    ];

    $form['email_notification'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Email Notifications'),
    ];

    $form['email_notification']['enable_admin_notifications'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable admin notifications'),
      '#description' => $this->t('Send an email to the site administrator when a new testimonial is submitted.'),
      '#default_value' => \Drupal::config('client_testimonials.settings')->get('enable_admin_notifications') ?: 0,
    ];

    $form['email_notification']['enable_user_notifications'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable user notifications'),
      '#description' => $this->t('Send an email to the user when their testimonial is approved.'),
      '#default_value' => \Drupal::config('client_testimonials.settings')->get('enable_user_notifications') ?: 0,
    ];

    $form['email_notification']['admin_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Admin email address'),
      '#description' => $this->t('The email address where new testimonial notifications will be sent.'),
      '#default_value' => \Drupal::config('client_testimonials.settings')->get('admin_email') ?: \Drupal::config('system.site')->get('mail'),
      '#states' => [
        'visible' => [
          ':input[name="enable_admin_notifications"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('enable_admin_notifications') && empty($form_state->getValue('admin_email'))) {
      $form_state->setErrorByName('admin_email', $this->t('Admin email address is required when admin notifications are enabled.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('client_testimonials.settings');
    $config->set('enable_admin_notifications', $form_state->getValue('enable_admin_notifications'))
      ->set('enable_user_notifications', $form_state->getValue('enable_user_notifications'))
      ->set('admin_email', $form_state->getValue('admin_email'))
      ->save();

    $this->messenger()->addStatus($this->t('The configuration options have been saved.'));
  }

}
