function keesCookieForm() {

    var cookieConsent = {};

    $("form#kees_cookie_form input:checkbox").each(function (index) {
        var value = $(this).is(":checked");
        var id = $(this).prop('name');

        cookieConsent[id] = value;

    });
    cookieConsent.primary_cookies = true; // set default for primary cookies

    var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
    document.cookie = "CookieConsent=" + JSON.stringify(cookieConsent) + ';expires=' + oneYearFromNow.toGMTString() + '; path=/';

    return true;
}

(function ($) {
    $(document).ready(function () {
        $('.consent__form__trigger').click(function () {
            $(this).toggleClass('minus');
            $(this).next().toggleClass('open');
        });
    });
}(jQuery));
