<?php

namespace Drupal\client_testimonials\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Defines the Testimonial entity.
 *
 * @ingroup client_testimonials
 *
 * @ContentEntityType(
 *   id = "testimonial",
 *   label = @Translation("Testimonial"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\client_testimonials\TestimonialListBuilder",
 *     "views_data" = "Drupal\client_testimonials\Entity\TestimonialViewsData",
 *     "form" = {
 *       "default" = "Drupal\client_testimonials\Form\TestimonialForm",
 *       "add" = "Drupal\client_testimonials\Form\TestimonialForm",
 *       "edit" = "Drupal\client_testimonials\Form\TestimonialForm",
 *       "delete" = "Drupal\client_testimonials\Form\TestimonialDeleteForm",
 *     },
 *     "access" = "Drupal\client_testimonials\TestimonialAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\client_testimonials\TestimonialHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "testimonial",
 *   translatable = FALSE,
 *   admin_permission = "administer testimonials",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "client_name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/testimonials/{testimonial}",
 *     "add-form" = "/admin/content/testimonials/add",
 *     "edit-form" = "/admin/content/testimonials/{testimonial}/edit",
 *     "delete-form" = "/admin/content/testimonials/{testimonial}/delete",
 *     "collection" = "/admin/content/testimonials",
 *   },
 *   field_ui_base_route = "entity.testimonial.settings"
 * )
 */
class Testimonial extends ContentEntityBase implements TestimonialInterface, EntityPublishedInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
      'status' => FALSE, // Unpublished by default.
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSubmittedDate() {
    return $this->get('submitted_date')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the testimonial author.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['client_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Client Name'))
      ->setDescription(t('The name of the client providing the testimonial.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['testimonial_text'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Testimonial Text'))
      ->setDescription(t('The text of the testimonial.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => -3,
        'rows' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['client_image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Client Image'))
      ->setDescription(t('The image of the client (optional).'))
      ->setSettings([
        'file_directory' => 'testimonials/images',
        'alt_field' => TRUE,
        'alt_field_required' => FALSE,
        'file_extensions' => 'png jpg jpeg',
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'image',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'image_image',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status']
      ->setDescription(t('A boolean indicating whether the testimonial is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 5,
      ])
      ->setDefaultValue(FALSE); // Unpublished by default.

    $fields['submitted_date'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Submitted Date'))
      ->setDescription(t('The time that the testimonial was submitted.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => -1,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the testimonial was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the testimonial was last edited.'));

    return $fields;
  }

}
