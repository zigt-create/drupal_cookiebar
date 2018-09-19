(function ($) {

  // When clicking on the OK button in the cookiebar
  $(document).on('click', '.kees-js-cookiebar-container a.kees-js-cookiebar-button', function(event) {
    // Variables
    var $object = $(this);

    // Prevent following the href
    event.preventDefault();

    // SetCookie function
    setCookie( ($object.attr("id") == "true")? "true": "false" );

    // Reload page to apply needed and remove unwanted cookies
    location.reload();
  });

  // When clicking on the radio buttons on the cookie page
  $(document).on('click', '#kees-js-cookiebar-settings input[type=radio]', function(event) {
    // Variables
    var $object = $(this);

    // SetCookie function
    setCookie( ($object.val() == "true")? "true": "false" );

    // Reload page to apply needed and remove unwanted cookies
    location.reload();
  });


  function setCookie(value){

    // Set cookiebar cookie for one year to the value which can be 'CookieAllowed' or 'CookieDisallowed'
    var oneYearFromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
    document.cookie = "CookieConsent="+ value +';expires=' + oneYearFromNow.toGMTString() + '; path=/';

    // Additional remove all other cookies if value is 'CookieDisallowed'
    if("false" == value) {
      // Get all cookies in array
      var getCookies = document.cookie.split(';');

      // Loop through all cookies
      $.each(getCookies, function(index, value){

        // Remove whitespaces from value
        value = value.replace(/^\s+|\s+$/g, '');

        // Split value on equal sign to extract name and cookievalue
        var splitValue = value.split('=');

        // Set variables with the name and value and time in past to remove the cookie
        var cookieRemoveExpireTime = new Date();
        cookieRemoveExpireTime.setTime(cookieRemoveExpireTime.getTime() - 1);

        var cookieName = splitValue[0];

        // If cookiename is not our own cookiebar cookie
        if('CookieConsent' != cookieName) {
          document.cookie = cookieName + "=; expires=" + cookieRemoveExpireTime.toGMTString() + "; path=/";
        }

      });
    }
  }

}(jQuery));
