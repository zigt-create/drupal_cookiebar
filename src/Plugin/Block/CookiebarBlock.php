<?php

namespace Drupal\cookiebar\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a cookiebar.
 *
 * @Block(
 *   id = "cookiebar_block",
 *   admin_label = "Cookiebar",
 *   category = "",
 * )
 */
class CookiebarBlock extends BlockBase
{

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        // Read settings
        $config = \Drupal::config('cookiebar.settings');

        return array(
            '#theme' => ($config->get('cookiebar.cookiebar_type') === "1")? "cookiebar_advanced" : "cookiebar",
            '#label' => array(
                '#markup' =>$config->get('cookiebar.label'),
            ),
            '#text' => array(
                '#markup' =>$config->get('cookiebar.text'),
            ),
            '#accept_button_text' => array(
                '#markup' =>$config->get('cookiebar.accept_button_text'),
            ),
            '#decline_button_text' => array(
                '#markup' =>$config->get('cookiebar.decline_button_text'),
            ),
            '#cookies' => $config->get('cookiebar.settings_cookies'),
            '#attached' => array(
                'library' => array(
                    ($config->get('cookiebar.cookiebar_type') === "1")? "cookiebar/cookiebar-advanced-js" : "cookiebar/cookiebar-default-js",
                ),
            ),
            '#cache' => array(
                "max-age" => 0,
                "contexts" => array(
                    "cookies:CookieConsent",
                ),
            ),
        );
    }
}
