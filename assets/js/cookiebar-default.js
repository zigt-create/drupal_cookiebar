/**
 * @file JS file for the cookiebar module
 *
 * @author KeesTM <developers@kees-tm.nl>
 * @author Tom Grootjans <tom@kees-tm.nl>
 */

/* Set default variables */
var keesCookieName = 'CookieConsent';

(function ($, Drupal) {
    Drupal.behaviors.kees_cookiebar_default = {
        attach: function (context, drupalSettings) {
          var cookiepagePath = drupalSettings.keesCookiebarConfig.cookiepagePath;
          var compliancyVersion = drupalSettings.keesCookiebarConfig.compliancyVersion;
          var currentUrl = drupalSettings.keesCookiebarConfig.currentUrl;
          var homeUrl = drupalSettings.keesCookiebarConfig.homeUrl;

            // Show cookiebar if cookies are not set or if user is on the cookies page
            if ((getCookieValue('version') == undefined || getCookieValue('version') != compliancyVersion) || getCookieValue('primary_cookies') != true || currentUrl == cookiepagePath) {
                $('#kees-cookiebar-container', context).once('cookiebar').show()
                $('.cookiebar', context).once('cookiebar').show()
            } else {
                $('#kees-cookiebar-container', context).once('cookiebar').hide()
                $('.cookiebar', context).once('cookiebar').hide()
            }

            $('#kees-cookiebar-container a.kees-js-cookiebar-button', context).click(function (e) {
                clicked(e, cookiepagePath, currentUrl, homeUrl, $(this).attr("id"), compliancyVersion);
            });
            $('.kees-js-cookiebar-container a.kees-js-cookiebar-button', context).click(function (e) {
                clicked(e, cookiepagePath, currentUrl, homeUrl, $(this).attr("id"), compliancyVersion);
            });
        }
    };

    function clicked (e, cookiepagePath, currentUrl, homeUrl, attrValue, compliancyVersion) {
      e.preventDefault(); //prevent link from redirecting

      var cookie = {};

      cookie["version"] = compliancyVersion;
      cookie.primary_cookies = attrValue; // set default for primary cookies

      var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
      document.cookie = "CookieConsent=" + btoa(JSON.stringify(cookie)) + ';expires=' + oneYearFromNow.toGMTString() + '; path=/';

      // Redirect or reload the page
      if (currentUrl == cookiepagePath) {
          window.location.href = homeUrl;
      } else {
          location.reload();
      }
    }


    /**
     * Get a settings from the cookie
     *
     * @param {string} name the name of the setting to get from the cookie defined in this file
     */
    function getCookieValue(name) {
      var cookie = getCookie();

      try {
          var object = JSON.parse(cookie);
      } catch (e) {
          return false;
      }

      return object[name];
  }
  /**
   * Get the cookie set by this module
   */
  function getCookie() {
      var value = "; " + document.cookie;
      var cookie = value.split("; " + keesCookieName + "=");

      var cookievalue = atob(cookie.pop().split(";").shift());
      console.log(cookievalue);

      return cookievalue;
  }
})(jQuery, Drupal);
