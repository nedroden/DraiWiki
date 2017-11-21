/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

$(function() {

    $("#to_top").click(function() {
        $('html, body').animate({
            scrollTop: $("#wrapper").offset().top
        }, 700);
    });

    $('#wrapper select').select2();

    let localeSwitcher = $('#locale_switcher');
    if (localeSwitcher.length) {
        localeSwitcher.change(function () {
            $(this).closest('form').trigger('submit');
        });
    }

    let localeSearchBox = $('#assign_translations_box');
    if (localeSearchBox.length) {
        $('#assign_translations_form').keypress(function(e) {
            if (e.which === 13) {
                return false;
            }
        });

        localeSearchBox.keypress(function(e) {
            if (e.which === 13) {
                updateTranslationResultsList();
            }
        });
    }
});

function requestConfirm(url) {
    new $.Zebra_Dialog(please_confirm, {
            width: 700,
            type: 'confirmation',
            buttons: [{
                caption: ok, callback: function() {
                    window.location = url
                }
            }]
        }
    );
}

function updateTranslationResultsList() {
    $('#results').hide(400);

    performAJAXRequest('/find/ajax/getresults;ignorelocales', {
        start: 0,
        terms: $('#assign_translations_box').val()
    }, function(msg, status, response) {

        // Calling this 'resultio' because 'results' doesn't work
        let selector = $('#resultio');

        selector.empty();

        $.each((response.responseJSON.data), function() {
            selector.append('<div class="search_result"><h1 class="translation_group_search_result"><a href="javascript:void(0);" onclick="selectTranslationGroup(\'' + this.id + '\')">' + this.title + '</a></h1></div>');
        });
    });
}

function selectTranslationGroup(article_id) {
    $('#article_id').val(article_id);
    $('#assign_translations_form').submit();
}