/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

function sysInfoToText() {
    let tableNodes = document.getElementById('system_information').childNodes;
    let resultHook = document.getElementById('result_hook');

    if (tableNodes.length != 0) {
        resultHook.innerHTML = '';
        resultHook.classList.add('code');
    }

    for (let node of tableNodes) {
        let childNodes = node.childNodes;

        if (childNodes.length === 0)
            continue;

        let count = 0;
        for (let grandChild of childNodes) {
            if (grandChild.tagName === undefined || grandChild.tagName.toLowerCase() != 'span')
                continue;

            resultHook.innerHTML += grandChild.innerHTML + ' ' + (count++ !== 0 ? '<br />' : '');
        }
    }
}