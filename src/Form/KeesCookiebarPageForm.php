<?php

namespace Drupal\kees_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class KeesCookiebarPageForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kees_cookiebar_page_form';
  }

  

/**
 * This method will create the settings form
 *
 * @param array $form
 * @param FormStateInterface $form_state
 * @return array
 */
public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('kees_cookiebar.settings');
    // Page title field.
    $form['title'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Title:'),
        '#default_value' => $config->get('kees_cookiebar.page_title'),
        '#description' => $this->t('Give your lorem ipsum generator page a title.'),
    );
    // Text field.
    $form['text'] = array(
        '#type' => 'text_format',
        '#title' => $this->t('Body'),
        '#default_value' => $config->get('kees_cookiebar.page_text'),
        '#description' => $this->t('Main text on the cookies page'),
        '#format'=> 'full_html',
    );
    // Accept button text field
    $form['accept_button_text'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Accept cookies button text:'),
        '#default_value' => $config->get('kees_cookiebar.page_accept_button_text'),
        '#description' => $this->t('Text to show on the button to accept the cookies'),
    );
    // Decline button text field
    $form['decline_button_text'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Decline cookies button text:'),
        '#default_value' => $config->get('kees_cookiebar.page_decline_button_text'),
        '#description' => $this->t('Text to show on the button to decline the cookies'),
    );

    return $form;
}

/**
 * Form validation for settings form
 *
 * @param array $form
 * @param FormStateInterface $form_state
 * @return void
 */
public function validateForm(array &$form, FormStateInterface $form_state) {
    // Not (yet) implemented
}

/**
 * Save to form by submitting it.
 *
 * @param array $form
 * @param FormStateInterface $form_state
 * @return parent::submitForm
 */
public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('kees_cookiebar.settings');
    $config->set('kees_cookiebar.page_title', $form_state->getValue('title'));
    $config->set('kees_cookiebar.page_text', $form_state->getValue('text')['value']);
    $config->set('kees_cookiebar.page_accept_button_text', $form_state->getValue('accept_button_text'));
    $config->set('kees_cookiebar.page_decline_button_text', $form_state->getValue('decline_button_text'));
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