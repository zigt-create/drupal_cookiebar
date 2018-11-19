// Settings
var cookieName = 'CookieConsent';


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

function getCookieValue(name) {
    var cookie = getCookie();

    try {
        var object = JSON.parse(cookie);
    } catch {
        return false;
    }

    return object[name];
}

function getCookie() {
    var value = "; " + document.cookie;
    var cookie = value.split("; " + cookieName + "=");

    if (cookie.length == 2) return cookie.pop().split(";").shift();
}
