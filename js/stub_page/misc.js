function email_focus() {
    if ($(this).val() == '������� ��� E-mail') {
        $(this).val('');
        $(this).removeClass('faded');
    }
}

function email_blur() {
    if ($(this).val() == '') {
        $(this).val('������� ��� E-mail');
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

//��������� �������
function declOfNum(number, titles)
{
    var cases = [2, 0, 1, 1, 1, 2];
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
}

function inducement_time()
{
    var sec_end = ['�������', '�������', '������'];
    var minut_end = ['������','������','�����'];
    var hour_end = ['���','����','�����'];
    var days_end = ['����','����','�����'];
    var week_end = ['������','������','������'];

}