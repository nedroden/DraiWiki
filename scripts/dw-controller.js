/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
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

function createConfirmationDialog(url, message) {
    new $.Zebra_Dialog(message, {
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

function requestConfirm(url) {
    createConfirmationDialog(url, please_confirm);
}

function requestConfirmMesg(url, message) {
    createConfirmationDialog(url, message);
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