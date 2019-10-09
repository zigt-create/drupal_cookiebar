<?php

namespace Drupal\advanced_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * {@inheritdoc}
 */
class AdvancedCookiebarPageForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advanced_cookiebar_page_form';
  }

  /**
   * This method will create the settings form.
   *
   * @param array $form
   *   Array with form to render.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current state of the form.
   *
   * @return array
   *   Returns the rendered form array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('advanced_cookiebar.settings');
    // Page title field.
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title:'),
      '#default_value' => $config->get('advanced_cookiebar.page_title'),
      '#description' => $this->t('Give your lorem ipsum generator page a title.'),
    ];
    // Intro text field.
    $form['intro'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Intro'),
      '#default_value' => $config->get('advanced_cookiebar.page_intro'),
      '#description' => $this->t('Intro text on the cookies page'),
      '#format' => 'basic_html',
    ];
    // Text field.
    $form['text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Body'),
      '#default_value' => $config->get('advanced_cookiebar.page_text'),
      '#description' => $this->t('Main text on the cookies page'),
      '#format' => 'basic_html',
    ];
    // Accept button text field.
    $form['accept_button_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Accept cookies button text:'),
      '#default_value' => $config->get('advanced_cookiebar.page_accept_button_text'),
      '#description' => $this->t('Text to show on the button to accept the cookies'),
    ];
    // Decline button text field.
    $form['decline_button_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Decline cookies button text:'),
      '#default_value' => $config->get('advanced_cookiebar.page_decline_button_text'),
      '#description' => $this->t('Text to show on the button to decline the cookies'),
    ];

    return $form;
  }

  /**
   * Form validation for settings form.
   *
   * @param array $form
   *   Array with form to render.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Not (yet) implemented.
  }

  /**
   * Save to form by submitting it.
   *
   * @param array $form
   *   Array with form to render.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state.
   *
   * @return parentsubmitForm
   *   Returns parent::submitForm().
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('advanced_cookiebar.settings');
    $config->set('advanced_cookiebar.page_title', $form_state->getValue('title'));
    $config->set('advanced_cookiebar.page_intro', $form_state->getValue('intro'));
    $config->set('advanced_cookiebar.page_text', $form_state->getValue('text')['value']);
    $config->set('advanced_cookiebar.page_accept_button_text', $form_state->getValue('accept_button_text'));
    $config->set('advanced_cookiebar.page_decline_button_text', $form_state->getValue('decline_button_text'));
    $config->save();

    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'advanced_cookiebar.settings',
    ];
  }

}
