/**
 * @file JS file for the cookiebar module
 *
 * @author KeesTM <developers@kees-tm.nl>
 * @author Tom Grootjans <tom@kees-tm.nl>
 */

 /* Set default variables */
var cookieName = 'CookieConsent';

/**
 * Jquery on document ready function(s)
 */
jQuery(document).ready(function () {

    // Show cookies block
    var cookiesPage = jQuery('#cookiebar_container').data('cookies-url');
    if (getCookieValue('primary_cookies') != true || cookiesPage == window.location.pathname ) {
        jQuery('#cookiebar_container').show();
    }

    jQuery('.consent__form__trigger').click(function () {
        jQuery(this).toggleClass('minus');
        jQuery(this).next().toggleClass('open');
    });
});

/**
 * Functions that sets a cookiebased on cookieform
 *      Function is called bij the sibmit button on the cookieform
 *
 * @returns {boolean} always true to continue the form sumbit
 */
function keesCookieForm() {
    var cookie = {};

    jQuery("form#kees_cookie_form input:checkbox").each(function (index) {
        var value = jQuery(this).is(":checked");
        var id = jQuery(this).prop('name');

        cookie[id] = value;

    });
    cookie.primary_cookies = true; // set default for primary cookies

    var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
    document.cookie = "CookieConsent=" + JSON.stringify(cookie) + ';expires=' + oneYearFromNow.toGMTString() + '; path=/';

    return true;
}

/**
 * Function to get a settings from the cookie
 *
 * @param {string} name the name of the setting to get from the cookie defined in this file
 */
function getCookieValue(name) {
    var cookie = getCookie();

    try {
        var object = JSON.parse(cookie);
    } catch {
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
