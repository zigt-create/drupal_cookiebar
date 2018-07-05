<?php
namespace Drupal\kees_cookiebar\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a cookiebar block with radio-buttons to change preference.
 *
 * @Block(
 *   id = "kees_cookieradio_block",
 *   admin_label = "KeesTM Radio Block",
 *   category = "Kees",
 * )
 */
class CookieRadioBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
      // Read settings 
    $config = \Drupal::config('kees_cookiebar.settings');

    return array(
        '#theme' => 'kees_radioblock',
        '#cookieValue' => array(
            '#markup' => $this->getCookieValue(),
        ),
        '#accept_button_text' => array(
            '#markup' =>$config->get('kees_cookiebar.page_accept_button_text'),
        ),
        '#decline_button_text' => array(
            '#markup' =>$config->get('kees_cookiebar.page_decline_button_text'),
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
    private function getCookieValue(){
        $cookie_name = "CookieConsent";

        if(isset($_COOKIE[$cookie_name])) {
            return $_COOKIE[$cookie_name];
        } 
    
    return false;
    }

}