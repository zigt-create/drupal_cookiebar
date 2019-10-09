

/* Set default variables */
var advancedCookieName = 'CookieConsent';

(function ($, Drupal) {
    Drupal.behaviors.advanced_cookiebar_default = {
        attach: function (context, drupalSettings) {
            var cookiepagePath = drupalSettings.cookiebarConfig.cookiepagePath;
            var currentUrl = drupalSettings.cookiebarConfig.currentUrl;
            var homeUrl = drupalSettings.cookiebarConfig.homeUrl;
            var versionNumber = drupalSettings.cookiebarConfig;

            // Show cookiebar if cookies are not set or if user is on the cookies page
            if ((getCookie() != "true" && getCookie() != "false") || (currentUrl == cookiepagePath)) {
                $('#advanced-cookiebar-container', context).once('cookiebar').show()
                $('.advanced-js-cookiebar-container', context).once('cookiebar').show()
            } else {
                $('#advanced-cookiebar-container', context).once('cookiebar').hide()
                $('.advanced-js-cookiebar-container', context).once('cookiebar').hide()
            }

            $('#advanced-cookiebar-container a.advanced-js-cookiebar-button', context).click(function (e) {
                clicked(e, cookiepagePath, currentUrl, homeUrl, $(this).attr("id"));
            });
            $('.advanced-js-cookiebar-container a.advanced-js-cookiebar-button', context).click(function (e) {
                clicked(e, cookiepagePath, currentUrl, homeUrl, $(this).attr("id"));
            });
        }
    };

    function clicked (e, cookiepagePath, currentUrl, homeUrl, attrValue) {
        e.preventDefault(); //prevent link from redirecting

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
        // Set cookiebar cookie for one year to the value which can be 'CookieAllowed' or 'CookieDisallowed'
        var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
        document.cookie = advancedCookieName + "=" + value + ';expires=' + oneYearFromNow.toGMTString() + '; path=/';

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
                if (advancedCookieName != cookieName) {
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
        var cookie = value.split("; " + advancedCookieName + "=");

        if (cookie.length == 2) return cookie.pop().split(";").shift();
    }
})(jQuery, Drupal);
