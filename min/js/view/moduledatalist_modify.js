$(function(){var t=$(".panel_detail").find("input[name=mapr_functiontype]").val();$("#mapr_functiontype option").each(function(){$(this).val()==t&&$(this).prop("selected",!0)}),$(".mdl_save").on("click",function(){$("#mapr_functiontype option").each(function(){if($(this).prop("selected")){var t=$(this).val();$(".panel_detail").find("input[name=mapr_functiontype]").val(t)}}),function(){var t=!0;return $(".check_unit").each(function(){var n=$(this);0===$.trim(n.val()).length&&(n.tooltip("show"),t=!1)}),t}()&&$(".panel_detail form").submit()}),$(".prompt_body_b .btn_yes").on("click",function(){$(".prompt_body_b").fadeOut(400)}),$(".prompt_body_b .btn_no").on("click",function(){$(".prompt_body_b").fadeOut(400)}),$(".prompt_body .btn_yes").on("click",function(){$(".prompt_body").fadeOut(400),$(".prompt_body").remove()})});