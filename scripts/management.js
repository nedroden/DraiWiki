/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

let url = null;

function activateUserTable() {
    url = '/management/users/ajax/getlist';

    performAJAXRequest(url, {
        "start": 0
    }, updateList);
}

function activateRecentEditsTable() {
    url = '/management/dashboard/ajax/getrecentedits';

    performAJAXRequest(url, {
        "start": 0
    }, updateList);
}

function sysInfoToText() {
    let table = $('.info_table');

    if (table.length) {
        let clipboardContent = '';

        table.find('div').each(function() {
            clipboardContent += $(this).find('span').first().text().slice(0, -1) + ' = ';
            clipboardContent += $(this).find('span').last().text() + '\n';
        });

        alert(clipboardContent);
    }
}