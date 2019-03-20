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
            var currentPath = window.location.pathname;

            $('#kees-cookiebar-container a.kees-js-cookiebar-button', context).click(function (event) {
                var $object = $(this);

                // Prevent following the href
                event.preventDefault();

                // SetCookie function
                setCookie(($object.attr("id") == "true") ? "true" : "false");

                // Reload page to apply needed and remove unwanted cookies 
                if (currentPath == cookiepagePath) {
                    window.location.href = "/";
                } else {
                    location.reload();
                }
            });

            // Show cookiebar if cookies are not set or if user is on the cookies page
            $('#kees-cookiebar-container', context).once('cookiebar').each(function () {
                if (getCookie() === undefined || currentPath == cookiepagePath) {
                    $(this).show();
                }
            });
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
