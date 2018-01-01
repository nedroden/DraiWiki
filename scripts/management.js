/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 */

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