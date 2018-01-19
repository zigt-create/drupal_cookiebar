<?php

namespace Drupal\kees_cookiebar\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a cookiebar.
 *
 * @Block(
 *   id = "kees_cookiebar_block",
 *   admin_label = "Kees-TM Cookiebar",
 *   category = "Kees",
 * )
 */
class CookiebarBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // read settings
    $config = \Drupal::config('kees_cookiebar.settings');
    
    $cookieSet = false;
    $cookieSet = $this->checkCookie();
    return array(
        '#theme' => 'kees_cookiebar',
        '#isCookie' => $cookieSet,
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
        '#attached' => array(
            'library' => array(
              'kees_cookiebar/cookiebar-css',
              'kees_cookiebar/cookiebar-js',
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

  /**
   * Checks if a coockie exists
   *
   * @return bool
   */
private function checkCookie(){
    $cookie_name = "CookieConsent";

    if(!isset($_COOKIE[$cookie_name])) {
        return false;
    } else {
        return true;
    }
  }

}