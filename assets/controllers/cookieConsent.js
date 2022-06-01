jQuery(function () {
    $('#allowCookie').click(function(){
        const oneYear = new Date(new Date().setFullYear(new Date().getFullYear() + 1))
        document.cookie = "cookie-consent=true;path=/;expires="+oneYear
        $('#cookieConsent').remove()
    })
    $('#cancelCookie').click(function(){
        $('#cookieConsent').remove()
    })
})