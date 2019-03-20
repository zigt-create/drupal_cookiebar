/**
 * @file JS file for the cookiebar module
 *
 * @author KeesTM <developers@kees-tm.nl>
 * @author Tom Grootjans <tom@kees-tm.nl>
 */

/* Set default variables */
var keesCookieName = 'CookieConsent';

(function ($, Drupal) {
    Drupal.behaviors.kees_cookiebar_advanced = {
        attach: function (context, drupalSettings) {
            var cookiepagePath = drupalSettings.keesCookiebarConfig.cookiepagePath;
            var currentPath = window.location.pathname;

            if (getCookieValue('primary_cookies') != true || currentPath == cookiepagePath) {
                $('#kees-cookiebar-container', context).show();
            }

            $('.consent__form__trigger', context).click(function () {
                $(this).toggleClass('minus');
                $(this).next().toggleClass('open');
            });

            // On form submit, save selected value as cookie
            $('form#kees-cookiebar-form', context).on('submit', function (e) {
                e.preventDefault();  //prevent form from submitting

                var cookie = {};

                $("form#kees-cookiebar-form input:checkbox").each(function () {
                    var value = $(this).is(":checked");
                    var id = $(this).prop('name');

                    cookie[id] = value;

                });
                cookie.primary_cookies = true; // set default for primary cookies

                var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
                document.cookie = "CookieConsent=" + JSON.stringify(cookie) + ';expires=' + oneYearFromNow.toGMTString() + '; path=/';

                // Redirect or reload the page
                if (currentPath == cookiepagePath) {
                    window.location.href = "/";
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
        var cookie = value.split("; " + keesCookieName + "=");

        if (cookie.length == 2) return cookie.pop().split(";").shift();
    }

})(jQuery, Drupal);
