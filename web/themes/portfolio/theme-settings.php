<?php

use Drupal\Core\Extension\Extension;
use Drupal\Core\Extension\ExtensionDiscovery;

use Drupal\system\Controller\ThemeController;
use Drupal\Core\Theme\ThemeManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Implementation of hook_form_system_theme_settings_alter()
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 *
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function portfolio_form_system_theme_settings_alter(&$form, &$form_state)
{
  // Create Omega Settings Object

  $form['core'] = array(
    '#type' => 'vertical_tabs',
    '#attributes' => array('class' => array('entity-meta')),
    '#weight' => -899
  );

  $form['theme_settings']['#group'] = 'core';
  $form['logo']['#group'] = 'core';
  $form['favicon']['#group'] = 'core';

  $form['theme_settings']['#open'] = FALSE;
  $form['logo']['#open'] = FALSE;
  $form['favicon']['#open'] = FALSE;

  // Custom settings in Vertical Tabs container
  $form['options'] = array(
    '#type' => 'vertical_tabs',
    '#attributes' => array('class' => array('entity-meta')),
    '#weight' => -999,
    '#default_tab' => 'edit-variables',
    '#states' => array(
      'invisible' => array(
        ':input[name="force_subtheme_creation"]' => array('checked' => TRUE),
      ),
    ),
  );

  /* --------- Setting social ----------------*/
  $form['social'] = array(
    '#type' => 'details',
    '#attributes' => array(),
    '#title' => t('social Links'),
    '#weight' => -999,
    '#group' => 'options',
    '#open' => FALSE,
  );

  $form['social']['facebook'] = array(
    '#type' => 'textfield',
    '#title' => t('Link facebook'),
    '#default_value' => theme_get_setting('facebook'),
  );
  $form['social']['twitter'] = array(
    '#type' => 'textfield',
    '#title' => t('Link Twitter'),
    '#default_value' => theme_get_setting('twitter'),
  );
  $form['social']['linkedin'] = array(
    '#type' => 'textfield',
    '#title' => t('Link Linkedin'),
    '#default_value' => theme_get_setting('linkedin'),
  );



  $form['footer'] = array(
    '#type' => 'details',
    '#attributes' => array(),
    '#title' => t('Footer Links'),
    '#weight' => -999,
    '#group' => 'options',
    '#open' => FALSE,
  );
  $form['footer']['copyright'] = array(
    '#type' => 'textfield',
    '#title' => t('Copyright Text'),
    '#default_value' => theme_get_setting('copyright'),
  );

  $form['header'] = array(
    '#type' => 'details',
    '#attributes' => array(),
    '#title' => t('Header Links'),
    '#weight' => -999,
    '#group' => 'options',
    '#open' => FALSE,
  );
  $form['header']['resume'] = array(
    '#type' => 'textfield',
    '#title' => t('Resume Link'),
    '#default_value' => theme_get_setting('resume'),
  );


  $form['hero_section'] = array(
    '#type' => 'details',
    '#attributes' => array(),
    '#title' => t('Hero Section'),
    '#weight' => -999,
    '#group' => 'options',
    '#open' => FALSE,
  );
  $form['hero_section']['hero_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Hero Title'),
    '#default_value' => theme_get_setting('hero_title'),
  );
  $form['hero_section']['hero_title2'] = array(
    '#type' => 'textfield',
    '#title' => t('Hero Title 2'),
    '#default_value' => theme_get_setting('hero_title2'),
  );
  $form['hero_section']['hero_description'] = array(
    '#type' => 'textfield',
    '#title' => t('Hero Description'),
    '#default_value' => theme_get_setting('hero_description'),
  );
  $form['hero_section']['hero_linkedin'] = array(
    '#type' => 'textfield',
    '#title' => t('Hero Linkedin'),
    '#default_value' => theme_get_setting('hero_linkedin'),
  );

  $form['hero_section']['hero_image'] = array(
    '#type' => 'textfield',
    '#title' => t('Hero Image'),
    '#default_value' => theme_get_setting('hero_image'),
  );

  $form['getintouch'] = array(
    '#type' => 'details',
    '#attributes' => array(),
    '#title' => t('Get in Touch'),
    '#weight' => -999,
    '#group' => 'options',
    '#open' => FALSE,
  );
  $form['getintouch']['getintouch_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#default_value' => theme_get_setting('getintouch_title'),
  );

  $form['getintouch']['getintouch_des'] = array(
    '#type' => 'textfield',
    '#title' => t('Description'),
    '#default_value' => theme_get_setting('getintouch_des'),
  );
  $form['getintouch']['getintouch_phone'] = array(
    '#type' => 'textfield',
    '#title' => t('Phone'),
    '#default_value' => theme_get_setting('getintouch_phone'),
  );
  $form['getintouch']['getintouch_email'] = array(
    '#type' => 'textfield',
    '#title' => t('Email'),
    '#default_value' => theme_get_setting('getintouch_email'),
  );
  $form['getintouch']['getintouch_location'] = array(
    '#type' => 'textfield',
    '#title' => t('Location'),
    '#default_value' => theme_get_setting('getintouch_location'),
  );

  $form['actions']['submit']['#value'] = t('Save');
}
