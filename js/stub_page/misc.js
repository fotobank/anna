function email_focus() {
    if ($(this).val() == 'Ââåäèòå Âàø E-mail') {
        $(this).val('');
        $(this).removeClass('faded');
    }
}

function email_blur() {
    if ($(this).val() == '') {
        $(this).val('Ââåäèòå Âàø E-mail');
        $(this).addClass('faded');
    }
}

function subscribe_submit() {

    $.post('toEmail?json=1', $('#subscribe_form').serialize(), subscribe_result, 'json');
    $('#subscribe_button').attr("disabled", "disabled");
    $('.form_message').fadeOut('fast');
    $('#loading_mess').fadeIn('fast');
    return false;
}


function subscribe_result(data) {
    $('#loading_mess').hide();
    log(data);
    display_message(data.type, data.msg);
    $('#subscribe_button').removeAttr("disabled");
}

function display_message(type, msg) {

    $('#form_message').removeClass().addClass(type).html(msg).fadeIn('slow');
    setTimeout(function () {
        $('#form_message').fadeOut('slow')
    }, 8000);
}

//ñêëîíåíèå âğåìåíè
function declOfNum(number, titles)
{
    var cases = [2, 0, 1, 1, 1, 2];
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
}

function inducement_time()
{
    var sec_end = ['ñåêóíäà', 'ñåêóíäû', 'ñåêóíä'];
    var minut_end = ['ìèíóòà','ìèíóòû','ìèíóò'];
    var hour_end = ['÷àñ','÷àñà','÷àñîâ'];
    var days_end = ['äåíü','äåíÿ','äåíåé'];
    var week_end = ['íåäåëÿ','íåäåëè','íåäåëü'];

}