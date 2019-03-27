<?php

namespace Drupal\kees_cookiebar\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a cookiebar.
 *
 * @Block(
 *   id = "kees_cookiebar_block",
 *   admin_label = "KeesTM Cookiebar",
 *   category = "Kees",
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
        $config = \Drupal::config('kees_cookiebar.settings');

        return array(
            '#theme' => ($config->get('kees_cookiebar.cookiebar_type') === "1")? "kees_cookiebar_advanced" : "kees_cookiebar_default",
            '#label' => array(
                '#markup' =>$config->get('kees_cookiebar.label'),
            ),
            '#text' => array(
                '#markup' =>$config->get('kees_cookiebar.text'),
            ),
            '#accept_button_text' => array(
                '#markup' =>$config->get('kees_cookiebar.accept_button_text'),
            ),
            '#decline_button_text' => array(
                '#markup' =>$config->get('kees_cookiebar.decline_button_text'),
            ),
            '#cookies' => $config->get('kees_cookiebar.settings_cookies'),
            '#attached' => array(
                'library' => array(
                    ($config->get('kees_cookiebar.cookiebar_type') === "1")? "kees_cookiebar/cookiebar-advanced-js" : "kees_cookiebar/cookiebar-default-js",
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
