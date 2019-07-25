<?php

namespace Drupal\kees_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Kees Cookiebar Remove Config form.
 */
class KeesCookiebarRemoveConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kees_cookiebar_remove_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    $key = \Drupal::request()->query->get('key');
    $title = \Drupal::request()->query->get('title');

    $form['key_text'] = [
      '#type' => 'hidden',
      '#value' => $key,
    ];

    $form['sure_text'] = [
      '#markup' => '<p>' . $this->t('Are you sure you want to delete this cookie-type: <b> @title </b> ?</p>', $title),
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#attributes' => [
        "class" => ['button--primary'],
      ],
      '#value' => t('Yes, delete'),
      '#suffix' => '<a href="' . Url::fromRoute('kees_cookiebar.config')->toString() . '">No, cancel</a>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('kees_cookiebar.settings');
    $cookies = $config->get('kees_cookiebar.settings_cookies');
    $key = $form_state->getValue('key_text');
    // If field is not empty.
    if (empty($key)) {
      $form_state->setErrorByName('key_text', $this->t('No key found! <a href="@url">Go back</a>', Url::fromRoute('kees_cookiebar.config')->toString()));
    }

    // If key exists.
    if (!array_key_exists($key, $cookies)) {
      $form_state->setErrorByName('key_text', $this->t('Key doesn\'t match with current configuration! <a href="@url">Go back</a>', Url::fromRoute('kees_cookiebar.config')->toString()));
    }

    // Primary cookies cannot be deleted.
    if ("primary_cookies" == $key) {
      $form_state->setErrorByName('key_text', $this->t('Primary cookies cannot be deleted! <a href="@url">Go back</a>', Url::fromRoute('kees_cookiebar.config')->toString()));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get variables.
    $key = $form_state->getValue('key_text');
    $config = $this->config('kees_cookiebar.settings');
    $cookies = $config->get('kees_cookiebar.settings_cookies');

    // Remove cookie from array.
    unset($cookies[$key]);
    // Save new array.
    $config->set('kees_cookiebar.settings_cookies', $cookies);
    $config->save();

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
