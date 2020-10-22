/**
 * @file JS file for the cookiebar module
 *
 * @author EstDigital <developers@estdigital.nl>
 * @author Tom Grootjans <tom@estdigital.nl>
 */

/* Set default variables */
var cookieName = 'CookieConsent';

(function ($, Drupal) {
    Drupal.behaviors.cookiebar_advanced = {
        attach: function (context, drupalSettings) {
            var cookiepagePath = drupalSettings.cookiebarConfig.cookiepagePath;
            var currentUrl = drupalSettings.cookiebarConfig.currentUrl;
            var homeUrl = drupalSettings.cookiebarConfig.homeUrl;

            if (getCookieValue('primary_cookies') != true || currentUrl == cookiepagePath) {
                $('#cookiebar-container', context).show();
            }

            $('.consent__form__trigger', context).click(function () {
                $(this).toggleClass('minus');
                $(this).next().toggleClass('open');
            });

            // On form submit, save selected value as cookie
            $('form#cookiebar-form', context).on('submit', function (e) {
                e.preventDefault();  //prevent form from submitting

                var cookie = {};

                $("form#cookiebar-form input:checkbox").each(function () {
                    var value = $(this).is(":checked");
                    var id = $(this).prop('name');

                    cookie[id] = value;

                });
                cookie.primary_cookies = true; // set default for primary cookies

                var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
                document.cookie = "CookieConsent=" + JSON.stringify(cookie) + ';expires=' + oneYearFromNow.toGMTString() + '; path=/';

                // Redirect or reload the page
                if (currentUrl == cookiepagePath) {
                    window.location.href = homeUrl;
                } else {
                    location.reload();
                }
            });
        }
    };

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
        var cookie = value.split("; " + cookieName + "=");

        if (cookie.length == 2) return cookie.pop().split(";").shift();
    }
})(jQuery, Drupal);
