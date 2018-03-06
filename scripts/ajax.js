/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

function performAJAXRequest(ajax_url, sendData, handler) {
    $.ajax({
        data: sendData,
        dataType: 'json',
        url: dw_url + '/index.php' + ajax_url,
        method: 'GET',
        success: handler,
        error: function() {
            alert(failed_to_retrieve_ajax_data);
        }
    });
}