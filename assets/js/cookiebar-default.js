/**
 * @file JS file for the cookiebar module
 *
 * @author EstDigital <developers@estdigital.nl>
 * @author Tom Grootjans <tom@estdigital.nl>
 */

/* Set default variables */
var CookieName = 'CookieConsent';

(function ($, Drupal) {
    Drupal.behaviors.cookiebar_default = {
        attach: function (context, drupalSettings) {
            console.log("joe");

            var cookiepagePath = drupalSettings.cookiebarConfig.cookiepagePath;
            var currentUrl = drupalSettings.cookiebarConfig.currentUrl;
            var homeUrl = drupalSettings.cookiebarConfig.homeUrl;

            // Show cookiebar if cookies are not set or if user is on the cookies page
            if ((getCookie() != "true" && getCookie() != "false") || (currentUrl == cookiepagePath)) {
                console.log("Show cookiebar");
                $('#cookiebar-container', context).once('cookiebar').show()
                $('.js-cookiebar-container', context).once('cookiebar').show()
            } else {
                console.log("Do not show cookiebar");
                $('#cookiebar-container', context).once('cookiebar').hide()
                $('.js-cookiebar-container', context).once('cookiebar').hide()
            }

            $('#cookiebar-container button,a.js-cookiebar-button', context).click(function (e) {
                clicked(e, cookiepagePath, currentUrl, homeUrl, $(this).attr("id"));
            });
            $('.js-cookiebar-container button,a.js-cookiebar-button', context).click(function (e) {
                clicked(e, cookiepagePath, currentUrl, homeUrl, $(this).attr("id"));
            });
        }
    };

    function clicked (e, cookiepagePath, currentUrl, homeUrl, attrValue) {
        console.log("Cookiebar is clicked");
        // Prevent link from redirecting
        e.preventDefault(); 
        
        // SetCookie function
        setCookie((attrValue == "true") ? "true" : "false");

        // Redirect or reload the page
        if (currentUrl == cookiepagePath) {
            window.location.href = homeUrl;
        } else {
            location.reload();
        }
    }

    // Helper function to set the cookie
    function setCookie(value) {
        console.log("Set cookie");
        // Set cookiebar cookie for one year to the value which can be 'CookieAllowed' or 'CookieDisallowed'
        var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
        document.cookie = CookieName + "=" + value + ';expires=' + oneYearFromNow.toGMTString() + '; path=/';

        // Additional remove all other cookies if value is 'CookieDisallowed'
        if ("false" == value) {
            console.log("Value is false");
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
                if (CookieName != cookieName) {
                    document.cookie = cookieName + "=; expires=" + cookieRemoveExpireTime.toGMTString() + "; path=/";
                }
            });
        }
    }

    /**
     * Get the cookie set by this module
     */
    function getCookie() {
        console.log("get cookie")
        var value = "; " + document.cookie;
        var cookie = value.split("; " + CookieName + "=");

        if (cookie.length == 2) return cookie.pop().split(";").shift();
    }
})(jQuery, Drupal);
