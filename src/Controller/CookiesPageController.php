<?php
namespace Drupal\kees_cookiebar\Controller;

use Drupal\Core\Controller\ControllerBase;


class CookiesPageController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function content() {
    // Read settings
    $config = \Drupal::config('kees_cookiebar.settings');

    return array(
        '#theme' => 'kees_cookiesPage',
        '#isCookie' => $cookieSet,
        '#title' => array(
            '#markup' =>$config->get('kees_cookiebar.page_title'),
        ),
        '#text' => array(
            '#markup' =>$config->get('kees_cookiebar.page_text'),
        ),
        '#accept_button_text' => array(
            '#markup' =>$config->get('kees_cookiebar.page_accept_button_text'),
        ),
        '#decline_button_text' => array(
            '#markup' =>$config->get('kees_cookiebar.page_decline_button_text'),
        ),
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
  
    private function getCookieValue(){
        $cookie_name = "CookieConsent";

        if(isset($_COOKIE[$cookie_name])) {
            return $_COOKIE[$cookie_name];
        } 
        return false;
    }

}