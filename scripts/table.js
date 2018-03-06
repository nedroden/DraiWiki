/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

// Since we need the url later, we're saving it right here
let url = null;

function activateTable(setUrl) {
    url = setUrl;

    performAJAXRequest(url, {
        "start": 0
    }, updateList);
}

function displayPagination(currentStart, totalRecords, recordsPerPage) {
    let numberOfPages = Math.round((totalRecords - 1) / recordsPerPage);
    let currentPage = 1 + Math.round((Number(currentStart) + 1) / recordsPerPage);

    if (numberOfPages * recordsPerPage < totalRecords)
        numberOfPages++;

    // 1 +
    let pages = [];

    let i = 1;

    let separator1 = false;
    let separator2 = false;

    while (i <= numberOfPages) {
        if(i === 1 || i === currentPage || (i <= currentPage + 2 && i >= currentPage - 2) || i === numberOfPages){
            pages.push('<a href="javascript:void(0);" data-page="' + i + '" onclick="changePage(\'' + i + '\', ' + recordsPerPage + ');" class="page page_normal">' + i + '</a>');
        }
        else{
            if(i < currentPage + 2 && separator1 === false){
                pages.push('<span class="page page_separator">...</span>');
                separator1 = true;
            }
            else{
                if(i > currentPage - 2 && separator2 === false){
                    pages.push('<span class="page page_separator">...</span>');
                    separator2 = true;
                }
            }
        }
        i++;
    }

    $('.pagination').html(pages.join(''));
    $('.pagination a.page[data-page="' + currentPage + '"]').removeClass('page_normal').addClass('page_current');
}

function updateTable(response) {
    result = response.responseJSON;

    // Display a message similar to 'Showing X of X results'
    $('.count').html(sprintf(showing_results, Number(result.start) + 1, result.end, result.total_records));

    displayPagination(result.start, result.total_records, result.displayed_records);
}

function updateList(msg, status, response) {
    updateTable(response);
    updateTableData(response.responseJSON.data);
}

function changePage(pageID, recordsPerPage) {
    performAJAXRequest(url, {
        "start": (pageID - 1) * recordsPerPage
    }, updateList);
}

function updateTableData(data) {
    let selector = $('#user_list tbody');
    let elements = [];

    selector.find('tr').remove();

    $.each(data, function(key, obj) {
        elements = [];
        $.each(obj, function(subKey, subValue) {
            elements.push('<td>' + subValue + '</td>');
        });

        selector.append('<tr>' + elements.join() + '</tr>');
    });
}