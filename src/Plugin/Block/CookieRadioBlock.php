<?php
namespace Drupal\kees_cookiebar\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a cookiebar.
 *
 * @Block(
 *   id = "kees_cookieradio_block",
 *   admin_label = "Kees-TM Radio Block",
 *   category = "Kees",
 * )
 */
class CookieRadioBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
        '#theme' => 'kees_radioblock',
        '#cookieValue' => array(
            '#markup' => $this->getCookieValue(),
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