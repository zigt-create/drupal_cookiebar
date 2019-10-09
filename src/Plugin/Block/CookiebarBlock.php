<?php

namespace Drupal\advanced_cookiebar\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a cookiebar.
 *
 * @Block(
 *   id = "advanced_cookiebar_block",
 *   admin_label = "Advanced Cookiebar",
 *   category = "Cookiebar",
 * )
 */
class CookiebarBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Read settings.
    $config = \Drupal::config('advanced_cookiebar.settings');

    return [
      '#theme' => ($config->get('advanced_cookiebar.cookiebar_type') === "1") ? "advanced_cookiebar_advanced" : "advanced_cookiebar",
      '#label' => [
        '#markup' => $config->get('advanced_cookiebar.label'),
      ],
      '#text' => [
        '#markup' => $config->get('advanced_cookiebar.text'),
      ],
      '#accept_button_text' => [
        '#markup' => $config->get('advanced_cookiebar.accept_button_text'),
      ],
      '#decline_button_text' => [
        '#markup' => $config->get('advanced_cookiebar.decline_button_text'),
      ],
      '#cookies' => $config->get('advanced_cookiebar.settings_cookies'),
      '#attached' => [
        'library' => [
          ($config->get('advanced_cookiebar.cookiebar_type') === "1") ? "advanced_cookiebar/cookiebar-advanced-js" : "advanced_cookiebar/cookiebar-default-js",
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

}
