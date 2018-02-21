$(function(){
// 修改、新增、刪除之後在頁面會跳出的小視窗，確認的按鈕
$('.btn_yes').on('click', function() {
  $('.prompt_body').fadeOut(400, function() {
    $('.prompt_body').remove();
  });
})


// 存檔按鈕
$('.usd_save').on('click',function(){
	var isFormValid = checkForm();
	if(isFormValid){
		var pwd = $('[name=usd_pwd]').val();
    var pwd_c = $('#usd_pwd_confirm').val();
    if(pwd != '' && pwd_c != ''){
      if(pwd != pwd_c){
        isFormValid = false;
        alert('密碼不ㄧ致，請重新輸入。');
        $('[name=usd_pwd]').val('');
        $('#usd_pwd_confirm').val('');
      }
    }
	}
	if(isFormValid){
		$('form').submit();
	}
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