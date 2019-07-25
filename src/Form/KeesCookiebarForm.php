<?php

namespace Drupal\kees_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\kees_cookiebar\Helper\ConfigHelper;

/**
 * Kees Cookiebar Form.
 */
class KeesCookiebarForm extends ConfigFormBase {
  /**
   * Config helper.
   *
   * @var [type]
   */
  protected $ConfigHelper;

  /**
   * Construct function.
   */
  public function __construct() {
    $this->ConfigHelper = new ConfigHelper();

    $cookies = $this->ConfigHelper->base_config->get('kees_cookiebar.settings_cookies');
    $type = $this->ConfigHelper->base_config->get('kees_cookiebar.cookiebar_type');
    $path = $this->ConfigHelper->base_config->get('kees_cookiebar.cookiepage_path');

    if (empty($cookies) || ($type !== "0" && $type !== "1") || empty($path)) {
      drupal_set_message(t('You need to run updates on the <a href="/update.php">update.php</a> page'), 'warning');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kees_cookiebar_form';
  }

  /**
   * This method will create the settings form.
   *
   * @param array $form
   *   Array with form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form_state.
   *
   * @return array
   *   Returns edited array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);

    // Get config value.
    $cookiebar_type = $this->ConfigHelper->base_config->get('kees_cookiebar.cookiebar_type');

    // Language links.
    $form = $this->ConfigHelper->addLanguageLinks($form);

    // Page title field.
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label:'),
      '#default_value' => $this->ConfigHelper->translatable_config->get('kees_cookiebar.label'),
      '#description' => $this->t('Bold text at the beginning of the cookiebar.'),
    ];

    // Text field.
    $form['text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description:'),
      '#default_value' => $this->ConfigHelper->translatable_config->get('kees_cookiebar.text'),
      '#description' => $this->t('Main text on the center of the cookiebar.'),
    ];

    // Accept button text field.
    $title = ($cookiebar_type == "1") ? "Submit cookies button text:" : "Accept cookies button text:";
    $description = ($cookiebar_type == "1") ? "Text to show on the button to submit cookie preference" : "Text to show on the button to accept the cookies";
    $form['accept_button_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t($title),
      '#default_value' => $this->ConfigHelper->translatable_config->get('kees_cookiebar.accept_button_text'),
      '#description' => $this->t($description),
    ];

    // Decline button text field.
    if ($cookiebar_type == "0") {
      $form['decline_button_text'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Decline cookies button text:'),
        '#default_value' => $this->ConfigHelper->translatable_config->get('kees_cookiebar.decline_button_text'),
        '#description' => $this->t('Text to show on the button to decline the cookies'),
      ];
    }

    return $form;
  }

  /**
   * Form validation for settings form.
   *
   * @param array $form
   *   Array with form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form_state.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Not (yet) implemented.
  }

  /**
   * Save to form by submitting it.
   *
   * @param array $form
   *   Array with form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form_state.
   *
   * @return parentsubmitForm
   *   Returns parent::submitForm().
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->ConfigHelper->translatable_config->set('kees_cookiebar.label', $form_state->getValue('label'));
    $this->ConfigHelper->translatable_config->set('kees_cookiebar.text', $form_state->getValue('text'));

    $this->ConfigHelper->translatable_config->set('kees_cookiebar.accept_button_text', $form_state->getValue('accept_button_text'));
    if ($this->ConfigHelper->base_config->get('kees_cookiebar.cookiebar_type') == "0") {
      $this->ConfigHelper->translatable_config->set('kees_cookiebar.decline_button_text', $form_state->getValue('decline_button_text'));
    }

    $this->ConfigHelper->translatable_config->save();

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
