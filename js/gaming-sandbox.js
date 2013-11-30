/**
 * Created with JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 6/15/13
 * Time: 8:37 PM
 * To change this template use File | Settings | File Templates.
 */
$.fn.classList = function() {
    return $(this).attr('class').split(/\s+/);
};

$(function() {
    $("#gs-game-selector").change(function() {
        if($(this).val() != '') {
            document.location = 'http://' + $(this).val() + ".gamingsandbox.com";
        }
    });

    $('input.required').each(function() {
        $(this).closest('div').addClass('required');
        $(this).attr('required', 'required');
    });

    $(document)
        .on('focus', '.field-hint', function () {
            if ($(this).val() == $(this).attr('fieldname')) {
                $(this).val('').css('color', '#000000');
            }
            else {
                $(this).val('').css('color', '#aaaaaa');
            }
        })
        .on('blur', '.field-hint', function () {
            if ($.trim($(this).val()) == '') {
                $(this).val($(this).attr('fieldname')).css('color', '#aaaaaa');
            }
            else {
                $(this).css('color', '#000000');
            }
        });

});

jQuery.fn.getValue = function() {
    if($(this).hasClass('field-hint')) {
        if($(this).val() === $(this).attr('fieldname')) {
            return '';
        }
        else {
            return $(this).val();
        }
    }
    else {
        return $(this).val();
    }
};