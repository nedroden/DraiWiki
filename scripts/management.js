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
    let numberOfPages = Math.round((totalRecords - 1) / recordsPerPage) + 1;
    let currentPage = 1 + Math.round((Number(currentStart) + 1) / recordsPerPage);

    let pages = [];

    let i = 1;
    while (i <= numberOfPages) {
        if (currentPage === i) {
            pages.push('<a href="javascript:void(0);" onclick="changeUserlistPage(\'' + i + '\', ' + recordsPerPage + ');" class="page page_current">' + i + '</a>');
            i++;
        }

        else if (((numberOfPages - currentPage) < 4 && currentPage - i <= 4) || (currentPage <= 4 && i <= 4)) {
            pages.push('<a href="javascript:void(0);" onclick="changeUserlistPage(\'' + i + '\', ' + recordsPerPage + ');" class="page page_normal">' + i + '</a>');
            i++;
        }

        else if (i === 1 || i === numberOfPages || (currentPage - i <= 2 && currentPage - i >= 0) || (i > currentPage && i - currentPage <= 2)) {
            pages.push('<a href="javascript:void(0);" onclick="changeUserlistPage(\'' + i + '\', ' + recordsPerPage + ');" class="page page_normal">' + i + '</a>');
            i++;
        }

        else {
            pages.push('<span class="page page_separator">...</span>');

            if (numberOfPages - currentPage > 4 && i < currentPage)
                i = currentPage - 2;
            else if (i <= (currentPage - 4))
                i = currentPage - 3;
            else if (i < currentPage)
                i++;
            else if (i > currentPage)
                i = numberOfPages;
        }
    }

    $('.pagination').html(pages.join(''));
}