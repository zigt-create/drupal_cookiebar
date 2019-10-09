<?php

namespace Drupal\advanced_cookiebar\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * {@inheritdoc}
 */
class CookiesPageController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Returns array with content
   */
  public function content() {
    // Read settings.
    $config = \Drupal::config('advanced_cookiebar.settings');

    return [
      '#theme' => 'advanced_cookiesPage',
      '#title' => [
        '#markup' => $config->get('advanced_cookiebar.page_title'),
      ],
      '#intro' => [
        '#markup' => $config->get('advanced_cookiebar.page_intro'),
      ],
      '#text' => [
        '#markup' => $config->get('advanced_cookiebar.page_text'),
      ],
      '#accept_button_text' => [
        '#markup' => $config->get('advanced_cookiebar.page_accept_button_text'),
      ],
      '#decline_button_text' => [
        '#markup' => $config->get('advanced_cookiebar.page_decline_button_text'),
      ],
      '#cookieValue' => [
        '#markup' => $this->getCookieValue(),
      ],
      '#attached' => [
        'library' => [
          'advanced_cookiebar/cookiebar-js',
        ],
      ],
      '#cache' => [
        "max-age" => 0,
        "contexts" => [
          "cookies:CookieConsent",
        ],
      ],
    ];
  }

  /**
   * Read the value of the cookie used to track the users preference.
   *
   * @return mixed
   *   Returns either the set cookie or false.
   */
  private function getCookieValue() {
    $cookie_name = "CookieConsent";

    if (isset($_COOKIE[$cookie_name])) {
      return $_COOKIE[$cookie_name];
    }
    return FALSE;
  }

}
