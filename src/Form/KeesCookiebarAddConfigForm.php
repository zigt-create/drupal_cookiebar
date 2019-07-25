<?php

namespace Drupal\kees_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Drupal\kees_cookiebar\Helper\ConfigHelper;

/**
 * Kees Cookiebar Add Config Form.
 */
class KeesCookiebarAddConfigForm extends ConfigFormBase {
  /**
   * Is edit.
   *
   * @var bool
   */
  protected $isEdit = FALSE;

  /**
   * Config helper.
   *
   * @var [type]
   */
  protected $ConfigHelper;
  /**
   * User selected Key.
   *
   * @var [type]
   */
  protected $userSelectedKey;

  /**
   * Construct.
   */
  public function __construct() {
    $this->ConfigHelper = new ConfigHelper();

    $requested_key = \Drupal::request()->query->get('key');
    if (!empty($requested_key)) {
      if (array_key_exists($requested_key, $this->ConfigHelper->getTranslatedCookies())) {
        $this->userSelectedKey = $requested_key;
      }
      else {
        $url = ConfigHelper::getUrlWithQueryParameters();
        $options = $url->getOptions();
        if (isset($options['query']['key'])) {
          unset($options['query']['key']);
        }
        $url->setOptions($options);
        $response = new RedirectResponse($url->toString());
        $response->send();
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kees_cookiebar_add_config_form';
  }

  /**
   * This method will create the settings form.
   *
   * @param array $form
   *   Array with the current form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current state of the form.
   *
   * @return array
   *   Returns edited form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get the current user.
    $user = \Drupal::currentUser();

    // Form constructor.
    $form = parent::buildForm($form, $form_state);

    $cookies = $this->ConfigHelper->getTranslatedCookies();

    if (empty($this->userSelectedKey) && !$user->hasPermission('administer cookiebar settings')) {
      return $form;
    }

    if (array_key_exists($this->userSelectedKey, $cookies)) {
      // Edit existing.
      $this->isEdit = TRUE;
      $edit_cookie = $cookies[$this->userSelectedKey];

      // Language links.
      $form = $this->ConfigHelper->addLanguageLinks($form);
    }

    $form['cookie'] = [
      '#type' => 'fieldset',
      '#title' => ($this->isEdit) ? $this->t('Edit Cookie type') : $this->t('Add Cookie type') ,
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['cookie']['cookie_name'] = [
      '#placeholder' => 'Cookie name',
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => ($this->isEdit) ? $edit_cookie['label'] : NULL,
    ];
    $form['cookie']['cookie_key'] = [
      '#placeholder' => 'cookie_key',
      '#type' => 'textfield',
      '#title' => t('Key'),
      '#disabled' => ($this->isEdit) ? TRUE : FALSE,
      '#default_value' => ($this->isEdit) ? $this->userSelectedKey : NULL,
    ];
    if ($this->isEdit) {
      // Set value to be key so it cant be changed.
      $form['cookie']['cookie_key']['#value'] = $this->userSelectedKey;
    }
    $form['cookie']['desc'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Description'),
      '#description' => $this->t('Description of this cookie-type'),
      '#format' => 'basic_html',
      '#default_value' => ($this->isEdit) ? $edit_cookie['desc'] : NULL,
    ];

    return $form;
  }

  /**
   * Form validation for settings form.
   *
   * @param array $form
   *   Array with the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form_state.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // If key is machine readable.
    if (!preg_match('(^[a-z_]*$)', $form_state->getValue('cookie_key'))) {
      $form_state->setErrorByName('cookie_key', $this->t('Key should only have lowercase characters and lowercases! (and no spaces)'));
    }
    // If field is nog empty aka more than 1 character long.
    if (empty($form_state->getValue('cookie_key')) || strlen($form_state->getValue('cookie_key')) <= 1) {
      $form_state->setErrorByName('cookie_key', $this->t('Key field should not be empty! (field must contain more than 1 character)'));
    }
    if (empty($form_state->getValue('cookie_name')) || strlen($form_state->getValue('cookie_name')) <= 1) {
      $form_state->setErrorByName('cookie_name', $this->t('Title field should not be empty! (field must contain more than 1 character)'));
    }
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
    // Get existing cookies.
    $cookies = $this->ConfigHelper->getTranslatedCookies();

    // Get fields.
    $form_name = $form_state->getValue('cookie_name');
    $form_desc = $form_state->getValue('desc')['value'];
    $form_key = $form_state->getValue('cookie_key');

    $cookie_key = (!empty($this->userSelectedKey)) ? $this->userSelectedKey : $form_key;

    // Set changes cookie.
    $cookies[$cookie_key] = [
      'label' => $form_name,
      'desc' => $form_desc,
    ];

    // Save config.
    $this->ConfigHelper->translatable_config->set('kees_cookiebar.settings_cookies', $cookies);
    $this->ConfigHelper->translatable_config->save();

    // Redirect and return.
    $form_state->setRedirect('kees_cookiebar.config');
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
