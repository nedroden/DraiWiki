/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

function loadUserListAjax() {
    $('#user_list').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dw_url + '/index.php/management/users/ajax/getlist',
            dataSrc: 'data',
            type: 'GET'
        },
        columns: [
            {data: 'username'},
            {data: 'first_name'},
            {data: 'last_name'},
            {data: 'email_address'},
            {data: 'sex'}
        ],
        language: {
            search: table_search
        }
    });
}