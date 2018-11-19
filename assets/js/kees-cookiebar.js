// Settings
var cookieName = 'CookieConsent';


function keesCookieForm() {

    jQuery("form#kees_cookie_form input:checkbox").each(function (index) {
        var value = jQuery(this).is(":checked");
        var id = jQuery(this).prop('name');

        cookieConsent[id] = value;

    });
    cookieConsent.primary_cookies = true; // set default for primary cookies

    var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
    document.cookie = "CookieConsent=" + JSON.stringify(cookieConsent) + ';expires=' + oneYearFromNow.toGMTString() + '; path=/';

    return true;
}

jQuery(document).ready(function () {
    jQuery('.consent__form__trigger').click(function () {
        jQuery(this).toggleClass('minus');
        jQuery(this).next().toggleClass('open');
    });
});
