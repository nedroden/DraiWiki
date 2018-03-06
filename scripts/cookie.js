/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

$(document).ready(function() {
    window.cookieconsent.initialise({
        layout: 'cookieLayout',
        layouts: {
            cookieLayout: '<div id="cookie_warning"><span class="cookie_message">{{message}}</span><span class="cookie_dismiss_container">{{dismiss}}</span></div>'
        },
        content: {
            dismiss: ok,
            header: cookie_header,
            message: cookie_explained,
            link: cookie_click_here
        },
        elements: {
            message: '<strong>{{header}}</strong>&nbsp;<span id="cookieconsent:desc">{{message}} <a tabindex="0" class="cookie_link" href="{{href}}" target="_blank">{{link}}</a></span>',
            dismiss: '<a tabindex="0" class="cc-btn cc-dismiss cookie_dismiss">{{dismiss}}</a>'
        }
    })
});