/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

function showMessageBox(message, status) {
    let messageBox = $('.message_box');

    if (messageBox.hasClass('error') && status)
        messageBox.removeClass('error');

    if (messageBox.hasClass('success') && !status)
        messageBox.removeClass('success');

    messageBox.text(message).addClass(status ? 'success' : 'error').show(1000);

    setTimeout(function() {
        messageBox.hide(1000);
    }, 5000)
}