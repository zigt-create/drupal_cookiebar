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
            var currentUrl = drupalSettings.keesCookiebarConfig.currentUrl;
            var homeUrl = drupalSettings.keesCookiebarConfig.homeUrl;

            $('#kees-cookiebar-container a.kees-js-cookiebar-button', context).click(function (e) {
                e.preventDefault(); //prevent link from redirecting

                var $object = $(this);

                // SetCookie function
                setCookie(($object.attr("id") == "true") ? "true" : "false");

                // Redirect or reload the page
                if (currentUrl == cookiepagePath) {
                    window.location.href = homeUrl;
                } else {
                    location.reload();
                }
            });

            // Show cookiebar if cookies are not set or if user is on the cookies page
            if ((getCookie() != "true" && getCookie() != "false") || (currentUrl == cookiepagePath)) {
                $('#kees-cookiebar-container', context).once('cookiebar').show()
            }
        }
    };

    // Helper function to set the cookie
    function setCookie(value) {
        // Set cookiebar cookie for one year to the value which can be 'CookieAllowed' or 'CookieDisallowed' 
        var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
        document.cookie = keesCookieName + "=" + value + ';expires=' + oneYearFromNow.toGMTString() + '; path=/';

        // Additional remove all other cookies if value is 'CookieDisallowed'
        if ("false" == value) {
            // Get all cookies in array
            var getCookies = document.cookie.split(';');

            // Loop through all cookies
            $.each(getCookies, function (index, value) {

                // Remove whitespaces from value
                value = value.replace(/^\s+|\s+$/g, '');

                // Split value on equal sign to extract name and cookievalue
                var splitValue = value.split('=');

                // Set variables with the name and value and time in past to remove the cookie
                var cookieRemoveExpireTime = new Date();
                cookieRemoveExpireTime.setTime(cookieRemoveExpireTime.getTime() - 1);

                var cookieName = splitValue[0];

                // If cookiename is not our own cookiebar cookie
                if (keesCookieName != cookieName) {
                    document.cookie = cookieName + "=; expires=" + cookieRemoveExpireTime.toGMTString() + "; path=/";
                }
            });
        }
    }

    /**
     * Get the cookie set by this module
     */
    function getCookie() {
        var value = "; " + document.cookie;
        var cookie = value.split("; " + keesCookieName + "=");

        if (cookie.length == 2) return cookie.pop().split(";").shift();
    }
})(jQuery, Drupal);
