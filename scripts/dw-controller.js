/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

$(document).ready(function() {
    $('#dw-about-link').on('click', function (e) {
        e.preventDefault();
        new $.Zebra_Dialog({
                width: 600,
                source: {inline: $('#dw-about').html()},
                animation_speed_hide: 300,
                type: false,
                buttons: []
            }
        );
    });

    $("#to_top").click(function() {
        $('html, body').animate({
            scrollTop: $("#wrapper").offset().top
        }, 700);
    });
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