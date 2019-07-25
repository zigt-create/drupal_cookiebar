<?php

namespace Drupal\kees_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Kees Cookiebar Settings Form.
 */
class KeesCookiebarSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kees_cookiebar_settings_form';
  }

  /**
   * This method will create the settings form.
   *
   * @param array $form
   *   The Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   State of the current form.
   *
   * @return array
   *   Returns form with the new options/values provided.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);

    // Get config.
    $config = $this->config('kees_cookiebar.settings');
    $cookiebar_type = $config->get('kees_cookiebar.cookiebar_type');
    $cookiepage_path = $config->get('kees_cookiebar.cookiepage_path');

    // Build form.
    $options = [
      0 => '<b>Default:</b> yes/no cookiebar',
      1 => '<b>Advanced:</b> multiple options cookiebar block',
    ];
    $form['cookiebar_type'] = [
      '#type' => 'radios',
      '#title' => 'Cookiebar type:',
      '#options' => $options,
      '#default_value' => $cookiebar_type,
      '#description' => 'When changing this, all users will have to re-set there cookie preference.',
    ];

    $form['cookiepage_path'] = [
      '#type' => 'textfield',
      '#title' => 'Path to the cookies settings page',
      '#default_value' => $cookiepage_path,
      '#size' => 60,
      '#description' => 'E.g: /cookies /cookie-page or /privacy-settings<br>' .
      'On this path the cookiebar or cookieblock will always be visible.<br>' .
      'When a user sets their cookie preference on this path, they will be redirected to the homepage instead of reloading the page.',
      '#maxlength' => 128,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * Form validation for settings form.
   *
   * @param array $form
   *   The Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The state of the current form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validation for cookiepage_path.
    $field_name = 'cookiepage_path';
    // If path starts with a '/'.
    if (substr($form_state->getValue($field_name), 0, 1) !== "/") {
      $form_state->setErrorByName($field_name, $this->t('Path must start with a "/"'));
    }
    // If path ends with a '/'.
    if (substr($form_state->getValue($field_name), -1) === "/") {
      $form_state->setErrorByName($field_name, $this->t('Path should not end with a "/"'));
    }
    // If path length is shorter than 3 characters.
    if (strlen($form_state->getValue($field_name)) < 3) {
      $form_state->setErrorByName($field_name, $this->t('Path should be atleast 3 charaters long'));
    }
  }

  /**
   * Save to form by submitting it.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The state of the current form.
   *
   * @return parentsubmitForm
   *   returns submitForm with new values
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $cookiebar_type = $form_state->getValue('cookiebar_type');
    $cookiepage_path = $form_state->getValue('cookiepage_path');

    $config = $this->config('kees_cookiebar.settings');
    $config->set('kees_cookiebar.cookiebar_type', $cookiebar_type);
    $config->set('kees_cookiebar.cookiepage_path', $cookiepage_path);
    $config->save();

    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'kees_cookiebar.settings',
    ];
  }

}
