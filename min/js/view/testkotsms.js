$(function(){$(".send").click(function(){var o=!0,t=$("input[name=mobile]").val();10!=t.length&&(o=!1,$(".prompt_body_b .prompt_box_b").find("h2").text("所填的手機號碼未滿10碼"),$(".prompt_body_b").fadeIn(400)),isNaN(t)&&(o=!1,$(".prompt_body_b .prompt_box_b").find("h2").text("必須是純數字的手機號碼"),$(".prompt_body_b").fadeIn(400)),o&&$("form").submit()}),$(".kotsms_yes").click(function(){$(".prompt_body_b").fadeOut(400),$(".prompt_body_b .prompt_box_b").find("h2").text("")})});