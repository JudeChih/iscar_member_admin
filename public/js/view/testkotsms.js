$(function(){
    $('.send').click(function(){
        var send = true;
        var val = $('input[name=mobile]').val();
        // 判斷是否為10碼
        if(val.length != 10){
            send = false;
            $('.prompt_body_b .prompt_box_b').find('h2').text('所填的手機號碼未滿10碼');
            $('.prompt_body_b').fadeIn(400);
        }
        // 判斷是否都是數字
        if(isNaN(val)){
            send = false;
            $('.prompt_body_b .prompt_box_b').find('h2').text('必須是純數字的手機號碼');
            $('.prompt_body_b').fadeIn(400);
        }
        // 確認無誤才送出
        if(send){
            $('form').submit();
        }
    })

    $('.kotsms_yes').click(function(){
        $('.prompt_body_b').fadeOut(400);
        $('.prompt_body_b .prompt_box_b').find('h2').text('');
    })
})