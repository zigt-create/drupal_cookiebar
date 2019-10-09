<?php

namespace Drupal\advanced_cookiebar\Helper;

use Drupal\Core\Url;

/**
 * Configer helper class.
 */
class ConfigHelper {
  /**
   * Selected Langcode.
   *
   * @var [type]
   */
  public $selectedLangcode;
  /**
   * Languages.
   *
   * @var [type]
   */
  public $languages;
  /**
   * Default Language.
   *
   * @var [type]
   */
  public $defaultLanguage;
  /**
   * Default Langcode.
   *
   * @var [type]
   */
  public $defaultLangcode;
  /**
   * Base Config.
   *
   * @var [type]
   */
  public $baseConfig;
  /**
   * Selected Langcode.
   *
   * @var [type]
   */
  public $translatableConfig;

  /**
   * Construct function.
   */
  public function __construct() {
    // Set languages variables.
    $this->languages = \Drupal::languageManager()->getLanguages();
    if (array_key_exists(\Drupal::request()->query->get('hl'), $this->languages)) {
      $this->selectedLangcode = \Drupal::request()->query->get('hl');
    }
    $this->defaultLanguage = \Drupal::languageManager()->getDefaultLanguage();
    $this->defaultLangcode = $this->defaultLanguage->getId();
    unset($this->languages[$this->defaultLangcode]);

    // Set config.
    $this->baseConfig = \Drupal::configFactory()->getEditable('advanced_cookiebar.settings');
    $this->translatableConfig = $this->baseConfig;
    if (!empty($this->selectedLangcode)) {
      $this->translatableConfig = \Drupal::languageManager()->getLanguageConfigOverride($this->selectedLangcode, 'advanced_cookiebar.settings');
    }
  }

  /**
   * Implement addLanguageLinks method.
   *
   * @param array $form
   *   Contains translatable strings.
   *
   * @return array
   *   Retuns array with newly added translations
   */
  public function addLanguageLinks(array $form) : array {
    if (!empty($this->languages)) {
      $form['lang_link_heading'] = [
        '#type' => 'html_tag',
        '#tag' => 'h3',
        '#value' => 'Translate',
      ];
      $url = $this::getUrlWithQueryParameters();

      $options = $url->getOptions();
      if (isset($options['query']['hl'])) {
        $active = $options['query']['hl'];
        unset($options['query']['hl']);
      }
      else {
        $active = 'default';
      }
      $url->setOptions($options);

      $form['lang_link_' . $this->defaultLangcode] = [
        '#type' => 'link',
        '#title' => $this->defaultLanguage->getName(),
        '#attributes' => [
          'class' => [
            'lang_link',
            ($active == 'default') ? 'active' : '',
          ],
        ],
        '#url' => $url,
      ];
      foreach ($this->languages as $language) {
        $url = $this::getUrlWithQueryParameters();

        $options = $url->getOptions();
        $options['query']['hl'] = $language->getId();
        $url->setOptions($options);

        $form['lang_link_' . $language->getId()] = [
          '#type' => 'link',
          '#title' => $language->getName(),
          '#attributes' => [
            'class' => [
              'lang_link',
              ($active == $language->getId()) ? 'active' : '',
            ],
          ],
          '#url' => $url,
        ];
      }
      $form['lang_link_spacing'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => '&nbsp;',
      ];
    }

    return $form;
  }

  /**
   * Get Translated Cookies.
   *
   * @return array
   *   Returns all translated cookie options
   */
  public function getTranslatedCookies() : array {
    $cookies = $this->baseConfig->get('advanced_cookiebar.settings_cookies');

    foreach ($cookies as $key => $cookie) {
      $translatedconf = $this->translatableConfig->get('advanced_cookiebar.settings_cookies');

      if (isset($translatedconf[$key])) {
        if (isset($translatedconf[$key]['label'])) {
          $cookies[$key]['label'] = $translatedconf[$key]['label'];
        }

        if (isset($translatedconf[$key]['desc'])) {
          $cookies[$key]['desc'] = $translatedconf[$key]['desc'];
        }
      }
    }

    return $cookies;
  }

  /**
   * Get URL With Query Parameters.
   *
   * @return Drupal\Url
   *   Returns URL with query
   */
  public static function getUrlWithsQueryParameters() {
    return Url::fromRoute('<current>', [], ['query' => \Drupal::request()->query->all()]);
  }

}
