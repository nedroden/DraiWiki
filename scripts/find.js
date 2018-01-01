/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 */

function loadMoreSearchResults(start_at, current_terms) {
    performAJAXRequest('/find/ajax/getresults', {
        start: start_at,
        terms: current_terms
    }, function(msg, status, response) {
        let selector = $('#results');

        $.each((response.responseJSON.data), function() {
            selector.append('<div class="search_result"><h1><a href="' + this.href + '" target="_self">' + this.title + '</a></h1></div>');
        });

        if (response.responseJSON.end >= response.responseJSON.total_records)
            $('#more_results').hide();
        else
            $('#more_results').click(loadMoreSearchResults(end, current_terms));
    });
}