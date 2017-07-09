/* ------------------------------------------------*/
/*               disclaimer cookie                 */
/* ------------------------------------------------*/

$(document).ready(function(){showCookieDisclaimer()});

function showCookieDisclaimer(){
    if ( navigator.cookieEnabled===true ){
        if (typeof $.cookie("cookieAccepted") === 'undefined'){
            $('#cookie-bar').slideDown();
        }
    }
}

$(document).on('click','#cookie-accepted',function(){hideCookieDisclaimer()});

function hideCookieDisclaimer(){
    $('#cookie-bar').fadeOut();
    $.cookie("cookieAccepted","true",{expires: 100},{path: '/'}); //100 days
}

$(document).ready(function(){checkCookiesEnabled()});

function checkCookiesEnabled(){
    if (navigator.cookieEnabled===false){
        $('#cookie-disabled').show();
    }
    else {
        $('#cookie-disabled').hide();
    }
}


