/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

function activateUserTable() {
    performAJAXRequest('/management/users/ajax/getlist', {
        "start": 0
    }, updateUserList);
}

function changeUserlistPage(pageID, recordsPerPage) {
    performAJAXRequest('/management/users/ajax/getlist', {
        "start": (pageID - 1) * recordsPerPage
    }, updateUserList);
}

function updateUserTable(data) {
    $('#user_list tbody').find('tr').remove();

    $(data).each(function() {
        $('#user_list tbody').append('<tr><td>' + this.username + '</td><td>' + this.first_name + '</td><td>' + this.last_name + '</td><td>' + this.email_address + '</td><td>' + this.registration_date + '</td><td>' + this.primary_group + '</td><td>' + this.sex + '</td></tr>');
    });
}

function updateUserList(msg, status, response) {
    result = response.responseJSON;

    // Display a message similar to 'Showing X of X results'
    $('.count').html(sprintf(showing_results, result.start, result.end, result.total_records));

    displayPagination(result.start, result.total_records, result.displayed_records);
    updateUserTable(result.data);
}

function displayPagination(currentStart, totalRecords, recordsPerPage) {
    let numberOfPages = Math.round((totalRecords - 1) / recordsPerPage);
    let currentPage = 1 + Math.round((Number(currentStart) + 1) / recordsPerPage);


    // 1 + 
    let pages = [];

    let i = 1;

    // '<a href="javascript:void(0);" onclick="changeUserlistPage(\'' + i + '\', ' + recordsPerPage + ');" class="page page_current">' + i + '</a>'
    // pages.push('<a href="javascript:void(0);" onclick="changeUserlistPage(\'' + i + '\', ' + recordsPerPage + ');" class="page page_normal">' + i + '</a>');
    // pages.push('<span class="page page_separator">...</span>');
    seperator1 = false;
    seperator2 = false;
    while (i <= numberOfPages) {
       if(i == 1 || i == currentPage || (i <= currentPage + 2 && i >= currentPage - 2) || i == numberOfPages){
            pages.push('<a href="javascript:void(0);" data-page="' + i + '" onclick="changeUserlistPage(\'' + i + '\', ' + recordsPerPage + ');" class="page page_normal">' + i + '</a>');
        }
        else{
            if(i < currentPage + 2 && seperator1 == false){
                pages.push('<span class="page page_separator">...</span>');
                seperator1 = true;
            }
            else{
                if(i > currentPage - 2 && seperator2 == false){
                    pages.push('<span class="page page_separator">...</span>');
                    seperator2 = true;
                }
            }
        }
       i++;
    }

    $('.pagination').html(pages.join(''));
    $('.pagination a.page[data-page="' + currentPage + '"]').removeClass('page_normal').addClass('page_current');

}