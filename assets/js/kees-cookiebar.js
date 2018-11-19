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
    jQuery('.consent__form__trigger').click(function () {
        jQuery(this).toggleClass('minus');
        jQuery(this).next().toggleClass('open');
    });
});
