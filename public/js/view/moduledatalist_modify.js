$(function(){

// 設定查詢框的值
var is = $('.panel_detail').find('input[name=mapr_functiontype]').val();
$('#mapr_functiontype option').each(function(){
	if($(this).val() == is){
		$(this).prop('selected', true);
	}
})

// 存檔按鈕
$('.mdl_save').on('click',function(){
	var isFormValid = true;
	$('#mapr_functiontype option').each(function(){
		if($(this).prop('selected')){
			var is = $(this).val();
			$('.panel_detail').find('input[name=mapr_functiontype]').val(is);
		}
	})
	isFormValid = checkForm();
	if(isFormValid){
		$('.panel_detail form').submit();
	}
})

$('.prompt_body_b .btn_yes').on('click',function(){
	$('.prompt_body_b').fadeOut(400);
})

$('.prompt_body_b .btn_no').on('click',function(){
	$('.prompt_body_b').fadeOut(400);
})

$('.prompt_body .btn_yes').on('click',function(){
	$('.prompt_body').fadeOut(400);
	$('.prompt_body').remove();
})

// 表單送出前的判斷
function checkForm(){
	var isFormValid = true;
	$('.check_unit').each(function(){
		var $this = $(this);
		if($.trim($this.val()).length === 0){
			$this.tooltip('show');
			isFormValid = false;
		}
	})
	return isFormValid;
}
})