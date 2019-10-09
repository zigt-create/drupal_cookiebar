<?php

namespace Drupal\advanced_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * {@inheritdoc}
 */
class AdvancedCookiebarConfigForm extends ConfigFormBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */

  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advanced_cookiebar_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get the current user.
    $user = \Drupal::currentUser();

    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('advanced_cookiebar.settings');
    $cookies = $config->get('advanced_cookiebar.settings_cookies');

    if ($user->hasPermission('administer cookiebar settings')) {
      $form['add_link'] = [
        '#attributes' => [
          "class" => ['button', 'form-actions'],
        ],
        '#title' => $this->t('+ Add cookie-type'),
        '#type' => 'link',
        '#url' => Url::fromRoute('advanced_cookiebar.add_config'),
      ];
    }

    $form['mytable'] = [
      '#type' => 'table',
      '#header' => [$this->t('Label'), $this->t('Key'), $this->t('Operations')],
      '#empty' => $this->t('No cookies found'),
    ];

    foreach ($cookies as $key => $value) {
      // Some table columns containing raw markup.
      $form['mytable'][$key]['label'] = [
        '#plain_text' => $value['label'],
      ];
      $form['mytable'][$key]['key'] = [
        '#plain_text' => $key,
      ];
      $form['mytable'][$key]['operations'] = [
        '#type' => 'operations',
        '#links' => [],
      ];
      $form['mytable'][$key]['operations']['#links']['edit'] = [
        'title' => $this->t('Edit'),
        'url' => Url::fromRoute('advanced_cookiebar.add_config', ['key' => $key]),
      ];
      if ("primary_cookies" != $key && $user->hasPermission('administer cookiebar settings')) {
        $form['mytable'][$key]['operations']['#links']['delete'] = [
          'title' => $this->t('Delete'),
          'url' => Url::fromRoute('advanced_cookiebar.remove_config', ['key' => $key, 'title' => $value['label']]),
        ];
      }
    }

    // Hide save button by giving actions->submit a empty array.
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Not needed / no submit button on this page.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Not needed / no submit button on this page
    // Do nothing.
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
